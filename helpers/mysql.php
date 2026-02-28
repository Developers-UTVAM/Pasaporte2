<?php
class MyDatabase
{
	/** @var mysqli */
	private $conn;

	/**
	 * Create a new DB connection.
	 * $config can contain: host, user, pass, db, port
	 * Example: new MySQLDatabase(['host'=>'localhost','user'=>'root','pass'=>'','db'=>'mydb'])
     *
     * $db = new MySQLDatabase(['host' => 'localhost', 'user' => 'root', 'pass' => 'password123', 'db' => 'mydb']);
	 * @param array $config
	 * @throws Exception
	 */
	public function __construct(array $config = [])
	{
		if(empty($config)) {
			// Try to load from global $db if available
			global $db;
			if (!empty($db) && is_array($db)) {
				$config = $db;
			} else {
				if (file_exists(__DIR__ . '/../configs.php')) {
					include __DIR__ . '/../configs.php';
					if (!empty($db) && is_array($db)) {
						$config = $db;
					} else {
						throw new Exception('Database configuration not found in configs.php');
					}
				} else {
					throw new Exception('Database configuration not provided and configs.php not found');
				}
			}
		}
		$host = $config['host'] ?? ($config['servidor'] ?? 'localhost');
		$user = $config['user'] ?? ($config['usuario'] ?? 'root');
		$pass = $config['pass'] ?? ($config['contrasena'] ?? '');
		$db   = $config['db'] ?? ($config['basededatos'] ?? null);
		$port = $config['port'] ?? ($config['puerto'] ?? 3306);

		$this->conn = new mysqli($host, $user, $pass, $db, $port);
		if ($this->conn->connect_error) {
			throw new Exception('MySQL connect error: ' . $this->conn->connect_error);
		}
		$this->conn->set_charset('utf8mb4');
	}

	/** Close connection when object is destroyed */
	public function __destruct()
	{
		if ($this->conn instanceof mysqli) {
			$this->conn->close();
		}
	}

	/** Helper: determine mysqli bind types for an array of values */
	private function getTypes(array $values): string
	{
		$types = '';
		foreach ($values as $v) {
			if (is_int($v)) $types .= 'i';
			elseif (is_float($v) || is_double($v)) $types .= 'd';
			else $types .= 's';
		}
		return $types;
	}

	/** Helper: bind params to a statement (supports PHP's requirement for references) */
	private function bindParams(mysqli_stmt $stmt, string $types, array $params)
	{
		if ($types === '') return;
		$bindNames = [];
		$bindNames[] = $types;
		foreach ($params as $key => $value) {
			$bindNames[] = &$params[$key];
		}
		call_user_func_array([$stmt, 'bind_param'], $bindNames);
	}

	/** Insert a row. $data is associative array column=>value. Returns inserted id on success.
     *
     * $id = $db->insert('users', ['name' => 'Ruben', 'age' => 30]);
    */
	public function insert(string $table, array $data)
	{
		$cols = array_keys($data);
		$vals = array_values($data);
		$placeholders = implode(',', array_fill(0, count($cols), '?'));
		$columns = implode('`,`', $cols);
		$sql = "INSERT INTO `{$table}` (`{$columns}`) VALUES ({$placeholders})";

		$stmt = $this->conn->prepare($sql);
		if (!$stmt) throw new Exception('Prepare failed: ' . $this->conn->error);

		$types = $this->getTypes($vals);
		$this->bindParams($stmt, $types, $vals);
		if (!$stmt->execute()) {
			$err = $stmt->error;
			$stmt->close();
			throw new Exception('Execute failed: ' . $err);
		}
		$id = $this->conn->insert_id;
		$stmt->close();
		return $id;
	}

	/** Update rows. $data is assoc column=>value. $where is SQL fragment (without 'WHERE'), $whereParams are values for placeholders. Returns affected rows.
     *
     * $updated = $db->update('users', ['name' => 'Roberto'], 'id = ?', [$id]);
     */
	public function update(string $table, array $data, string $where, array $whereParams = [])
	{
		$cols = array_keys($data);
		$vals = array_values($data);
		$set = implode(', ', array_map(function ($c) { return "`{$c}` = ?"; }, $cols));
		$sql = "UPDATE `{$table}` SET {$set} WHERE {$where}";

		$stmt = $this->conn->prepare($sql);
		if (!$stmt) throw new Exception('Prepare failed: ' . $this->conn->error);

		$params = array_merge($vals, $whereParams);
		$types = $this->getTypes($params);
		$this->bindParams($stmt, $types, $params);
		if (!$stmt->execute()) {
			$err = $stmt->error;
			$stmt->close();
			throw new Exception('Execute failed: ' . $err);
		}
		$affected = $stmt->affected_rows;
		$stmt->close();
		return $affected;
	}

	/** Delete rows. $where is SQL fragment (without 'WHERE'), $params are values. Returns affected rows.
     *
     * $deleted = $db->delete('users', 'id = ?', [$id]);
     */
	public function delete(string $table, string $where, array $params = [])
	{
		$sql = "DELETE FROM `{$table}` WHERE {$where}";
		$stmt = $this->conn->prepare($sql);
		if (!$stmt) throw new Exception('Prepare failed: ' . $this->conn->error);

		$types = $this->getTypes($params);
		$this->bindParams($stmt, $types, $params);
		if (!$stmt->execute()) {
			$err = $stmt->error;
			$stmt->close();
			throw new Exception('Execute failed: ' . $err);
		}
		$affected = $stmt->affected_rows;
		$stmt->close();
		return $affected;
	}

	/** Select a single row. Returns associative array or null if not found. $where may be empty to select first row.
     *
     * $user = $db->select('users', 'id = ?', [$id]);
     */
	public function select(string $table, string $where = '', array $params = [])
	{
		$sql = "SELECT * FROM `{$table}`" . ($where !== '' ? " WHERE {$where}" : '') . " LIMIT 1";
		$stmt = $this->conn->prepare($sql);
		if (!$stmt) throw new Exception('Prepare failed: ' . $this->conn->error);

		$types = $this->getTypes($params);
		$this->bindParams($stmt, $types, $params);
		if (!$stmt->execute()) {
			$err = $stmt->error;
			$stmt->close();
			throw new Exception('Execute failed: ' . $err);
		}

		$result = $this->fetchAssocFromStmt($stmt);
		$stmt->close();
		return $result ?: null;
	}

	/** Select all matching rows. Returns array of associative arrays.
     *
     * $adults = $db->selectAll('users', 'age >= ?', [18]);
     */
	public function selectAll(string $table, string $where = '', array $params = []) : array
	{
		$sql = "SELECT * FROM `{$table}`" . ($where !== '' ? " WHERE {$where}" : '');
		$stmt = $this->conn->prepare($sql);
		if (!$stmt) throw new Exception('Prepare failed: ' . $this->conn->error);

		$types = $this->getTypes($params);
		$this->bindParams($stmt, $types, $params);
		if (!$stmt->execute()) {
			$err = $stmt->error;
			$stmt->close();
			throw new Exception('Execute failed: ' . $err);
		}

		$rows = [];
		// Try to use get_result (requires mysqlnd), otherwise fallback
		if (method_exists($stmt, 'get_result')) {
			$res = $stmt->get_result();
			while ($r = $res->fetch_assoc()) $rows[] = $r;
		} else {
			// Fallback binding
			$meta = $stmt->result_metadata();
			if ($meta) {
				$fields = $meta->fetch_fields();
				$bindVars = [];
				$row = [];
				foreach ($fields as $field) {
					$bindVars[] = &$row[$field->name];
				}
				call_user_func_array([$stmt, 'bind_result'], $bindVars);
				while ($stmt->fetch()) {
					$copy = [];
					foreach ($row as $k => $v) $copy[$k] = $v;
					$rows[] = $copy;
				}
			}
		}

		$stmt->close();
		return $rows;
	}

	/** Run a custom SELECT query. $params for prepared statement. Returns array of assoc rows.
     *
     * $rows = $db->query('SELECT id, name FROM `users` WHERE age > ?', [21]);
     */
	public function query(string $sql, array $params = []) : array
	{
		$stmt = $this->conn->prepare($sql);
		if (!$stmt) throw new Exception('Prepare failed: ' . $this->conn->error);
		$types = $this->getTypes($params);
		$this->bindParams($stmt, $types, $params);
		if (!$stmt->execute()) {
			$err = $stmt->error;
			$stmt->close();
			throw new Exception('Execute failed: ' . $err);
		}
		$rows = [];
		if (method_exists($stmt, 'get_result')) {
			$res = $stmt->get_result();
			while ($r = $res->fetch_assoc()) $rows[] = $r;
		} else {
			$meta = $stmt->result_metadata();
			if ($meta) {
				$fields = $meta->fetch_fields();
				$bindVars = [];
				$row = [];
				foreach ($fields as $field) {
					$bindVars[] = &$row[$field->name];
				}
				call_user_func_array([$stmt, 'bind_result'], $bindVars);
				while ($stmt->fetch()) {
					$copy = [];
					foreach ($row as $k => $v) $copy[$k] = $v;
					$rows[] = $copy;
				}
			}
		}
		$stmt->close();
		return $rows;
	}

	/** Fetch a single associative row from an executed statement. */
	private function fetchAssocFromStmt(mysqli_stmt $stmt)
	{
		if (method_exists($stmt, 'get_result')) {
			$res = $stmt->get_result();
			return $res->fetch_assoc();
		}
		$meta = $stmt->result_metadata();
		if (!$meta) return null;
		$fields = $meta->fetch_fields();
		$bindVars = [];
		$row = [];
		foreach ($fields as $field) {
			$bindVars[] = &$row[$field->name];
		}
		call_user_func_array([$stmt, 'bind_result'], $bindVars);
		if ($stmt->fetch()) {
			$copy = [];
			foreach ($row as $k => $v) $copy[$k] = $v;
			return $copy;
		}
		return null;
	}

	public function getFields(string $table) : array
	{
		$sql = "SHOW COLUMNS FROM `{$table}`";
		$stmt = $this->conn->prepare($sql);
		if (!$stmt) throw new Exception('Prepare failed: ' . $this->conn->error);
		if (!$stmt->execute()) {
			$err = $stmt->error;
			$stmt->close();
			throw new Exception('Execute failed: ' . $err);
		}
		$fields = [];
		if (method_exists($stmt, 'get_result')) {
			$res = $stmt->get_result();
			while ($r = $res->fetch_assoc()) $fields[] = $r['Field'];
		} else {
			$meta = $stmt->result_metadata();
			if ($meta) {
				$bindVars = [];
				$row = [];
				foreach ($meta->fetch_fields() as $field) {
					$bindVars[] = &$row[$field->name];
				}
				call_user_func_array([$stmt, 'bind_result'], $bindVars);
				while ($stmt->fetch()) {
					$fields[] = $row['Field'];
				}
			}
		}
		$stmt->close();
		return $fields;
	}
}
