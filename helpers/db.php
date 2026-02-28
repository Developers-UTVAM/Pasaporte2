<?php

/** For MySQL connection, queries, and table management. Uses MySQLi with prepared statements for security and simplicity. */
require_once __DIR__ . '/mysql.php';
$MyDBClass = MyDatabase::class;

class Table
{
	/** @var MyDatabase Static connection shared across all Table instances */
	private static $connection = null;

	/** @var string Table name for this instance */
	private $table;

	/**
	 * Initialize or retrieve the static database connection.
	 * If needed, creates a new MySQLDatabase instance from configs.
	 *
	 * @param array $config Optional config (host, user, pass, db, port)
	 * @return MySQLDatabase
	 * @throws Exception
	 */
	public static function getConnection(array $config = []) : MyDatabase
	{
        global $MyDBClass;
		if (self::$connection === null) {
			// If no config provided, try to load from configs.php
			if (empty($config)) {
				if (file_exists(__DIR__ . '/../configs.php')) {
					$db = include __DIR__ . '/../configs.php';
					$config = [
						'host' => $db['servidor'] ?? 'localhost',
						'user' => $db['usuario'] ?? 'root',
						'pass' => $db['contrasena'] ?? '',
						'db'   => $db['base_datos'] ?? null,
						'port' => $db['puerto'] ?? 3306,
					];
				}
			}
			self::$connection = new $MyDBClass($config);
		}
		return self::$connection;
	}

	/**
	 * Create a Table manager instance for the given table name.
	 * Automatically opens connection if not already open.
	 *
	 * $users = new Table('users');
	 *
	 * @param string $table Table name
	 * @param array $config Optional config for connection init
	 * @throws Exception
	 */
	public function __construct(string $table, array $config = [])
	{
		$this->table = $table;
		self::getConnection($config);
	}

	/**
	 * Insert a row into the table.
	 * Returns the inserted row id.
	 *
	 * Example:
     *
	 * $users = new Table('users');
     *
	 * $id = $users->insert(['name' => 'Ruben', 'age' => 30]);
	 *
	 * @param array $data Associative array column => value
	 * @return mixed Inserted id
	 * @throws Exception
	 */
	public function insert(array $data)
	{
		return self::$connection->insert($this->table, $data);
	}

	/**
	 * Select a single row from the table.
	 * Returns associative array or null if not found.
	 *
	 * Example:
	 * $users = new Table('users');
	 * $user = $users->select('id = ?', [5]);
	 *
	 * @param string $where SQL WHERE clause (without 'WHERE' keyword)
	 * @param array $params Parameter values for placeholders
	 * @return array|null Associative array or null
	 * @throws Exception
	 */
	public function select(string $where = '', array $params = [])
	{
		return self::$connection->select($this->table, $where, $params);
	}

	/**
	 * Select all rows matching the condition.
	 * Returns array of associative arrays.
	 *
	 * Example:
	 * $users = new Table('users');
	 * $adults = $users->selectAll('age >= ?', [18]);
	 *
	 * @param string $where SQL WHERE clause (without 'WHERE' keyword), empty for all rows
	 * @param array $params Parameter values for placeholders
	 * @return array Array of associative arrays
	 * @throws Exception
	 */
	public function selectAll(string $where = '', array $params = []) : array
	{
		return self::$connection->selectAll($this->table, $where, $params);
	}

	/**
	 * Update rows matching the condition.
	 * Returns number of affected rows.
	 *
	 * Example:
     *
	 * $users = new Table('users');
	 *
     * $updated = $users->update(['name' => 'Roberto'], 'id = ?', [5]);
	 *
	 * @param array $data Associative array column => new value
	 * @param string $where SQL WHERE clause (without 'WHERE' keyword)
	 * @param array $params Parameter values for WHERE clause
	 * @return int Number of affected rows
	 * @throws Exception
	 */
	public function update(array $data, string $where, array $params = [])
	{
		return self::$connection->update($this->table, $data, $where, $params);
	}

	/**
	 * Delete rows matching the condition.
	 * Returns number of affected rows.
	 *
	 * Example:
	 *
     * $users = new Table('users');
     *
	 * $deleted = $users->delete('id = ?', [5]);
	 *
	 * @param string $where SQL WHERE clause (without 'WHERE' keyword)
	 * @param array $params Parameter values for placeholders
	 * @return int Number of affected rows
	 * @throws Exception
	 */
	public function delete(string $where, array $params = [])
	{
		return self::$connection->delete($this->table, $where, $params);
	}

	/**
	 * Run a custom query on this table.
	 * Returns array of associative arrays.
	 * Useful for complex queries or JOINs.
	 *
	 * Example:
     *
	 * $users = new Table('users');
	 *
     * $results = $users->query('SELECT * FROM `users` WHERE age > ? ORDER BY name', [21]);
	 *
	 * @param string $sql Full SQL query
	 * @param array $params Parameter values
	 * @return array Array of associative arrays
	 * @throws Exception
	 */
	public function query(string $sql, array $params = []) : array
	{
		return self::$connection->query($sql, $params);
	}

	/**
	 * Get the table name for this instance.
     *
     * $tableName = $users->getTableName();
	 *
	 * @return string Table name
	 */
	public function getTableName() : string
	{
		return $this->table;
	}

	/**
	 * Get the static database connection instance.
	 * Useful if you need to run operations outside of this table.
     *
     * $db = Table::getDB();
     *
     * $custom = $db->selectAll('users');
	 *
	 * @return MySQLDatabase
	 */
	public static function getDB() : mixed
	{
		return self::$connection ?? self::getConnection();
	}
}
