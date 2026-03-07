<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once __DIR__ . '/../../helpers/vars.php';
include_once __DIR__ . '/../../helpers/db.php';
include_once __DIR__ . '/../permiso/model.php';
include_once __DIR__ . '/../perfil/model.php';

use \De\Set as Set;

class Usuario extends Model
{
    private static $db_config = null;
    private static $permisos = null;
    private static $perfiles = null;
    private static $tbl_usuario_tiene_permiso = null;
    private static $tbl_usuario_tiene_perfil = null;

    private $authenticated = false;

    public function __construct(array $config = [])
    {
        parent::__construct('usuario', config: $config);
        if(self::$db_config === null) {
            self::$db_config = $config;
        }
        if(self::$permisos === null) {
            self::$permisos = new Permiso($config);
        }
        if(self::$perfiles === null) {
            self::$perfiles = new Perfil($config);
        }
        if(self::$tbl_usuario_tiene_permiso ===  null) {
            self::$tbl_usuario_tiene_permiso = New Table("usuario_tiene_permiso", $config);
        }
        if(self::$tbl_usuario_tiene_perfil === null) {
            self::$tbl_usuario_tiene_perfil = New Table('usuario_tiene_perfil', $config);
        }
    }

    public function __tostring(): string
    {
        return $this->nombre ? trim($this->nombre . " " . $this->apaterno . " " . $this->amaterno) : "Usuario";
    }

    public function getAll(): array
    {
        $data = parent::getAll();
        uasort($data, function ($a, $b) {
            $cmp = strcmp($a['nombre'], $b['nombre']);
            if ($cmp !== 0) return $cmp;
            $cmp = strcmp($a['apaterno'], $b['apaterno']);
            if ($cmp !== 0) return $cmp;
            return strcmp($a['amaterno'], $b['amaterno']);
        });
        return $data;
    }

    public function save(): bool {
        if($this->password) {
            $info = password_get_info($this->password);
            if($info['algo'] === 0 || $info['algo'] ===  null) {
                $this->password = password_hash($this->password, PASSWORD_DEFAULT);
            }
        }
        $tmp_data = $this->data;
        if($this->pk) {
            unset($this->data["password"]);
            unset($this->data["username"]);
        }
        $this->data["activo"] = $this->data["activo"] ?? 0;
        $this->data["superusuario"] = $this->data["superusuario"] ?? 0;
        if(parent::save() || $this->pk)
        {
            $current_pk = $this->pk;
            $this->data = $tmp_data;
            $this->pk = $current_pk;
            self::$tbl_usuario_tiene_permiso->delete("usuario_id = ?", [$this->pk]);
            foreach (getvar("permisos") as $perm) {
                self::$tbl_usuario_tiene_permiso->insert(["usuario_id" => $this->pk, "permiso_id" => $perm]);
            }
            self::$tbl_usuario_tiene_perfil->delete("usuario_id = ?", [$this->pk]);
            foreach (getvar("perfiles") as $perm) {
                self::$tbl_usuario_tiene_perfil->insert(["usuario_id" => $this->pk, "perfil_id" => $perm]);
            }
            return true;
        }
        return false;
    }

    public function can($perms, $able_if_superuser = true, $able_if_authenticated = true): bool {
        if($able_if_superuser && $this->superusuario) { return true; }
        if($able_if_authenticated && !$this->is_authenticated()) { return false; }
        if(is_string($perms)) {
            list($tipo, $codename) = explode(".", $perms);
            if(($perm = self::$permisos->select("tipo = ? and codename = ?", [$tipo, $codename])) === null) {
                throw new Exception("No se ha encontrado el permiso: " . $perms);
            }
            if(self::$tbl_usuario_tiene_permiso->select("usuario_id = ? and permiso_id = ?", [$this->pk, $perm["id"]]) !== null) {
                return true;
            }
            foreach(self::$tbl_usuario_tiene_perfil->selectAll("usuario_id = ?", [$this->pk]) as $p_id) {
                $perfil = new Perfil(self::$db_config);
                if($perfil->can($perms)) {
                    return true;
                }
            }
        } else {
            foreach($perms as $perm) {
                if($this->can($perm)) {
                    return true;
                }
            }
        }
        return false;
    }

    public function todosLosPermisos(): array {
        return self::$permisos->getAll();
    }

    public function todosLosPerfiles(): array {
        return self::$perfiles->getAll();
    }

    public function hasProfile($profile_id): bool {
        return self::$tbl_usuario_tiene_perfil->select("perfil_id = ? and usuario_id = ?", [$profile_id, $this->pk]) !== null;
    }

    public function is_authenticated(): bool {
        return $this->authenticated;
    }

    public function checkLogin($usr, $pwd): Usuario {
        $hash_pwd = "";
        $usr = $this->select("username = ?", [$usr]);
        if($usr !== null) {
            $hash_pwd = $usr["password"];
        } else {
            $usr = $this->select("email = ?", [$usr]);
            if($usr !== null) {
                $hash_pwd = $usr["password"];
            }
        }
        if(!$hash_pwd) { return null; }
        if(password_verify($pwd, $hash_pwd)) {
            $user = new Usuario(self::$db_config);
            if($user->get($usr["id"])) {
                return $user;
            }
        }
        return null;
    }

    public function authenticate($usr, $pwd): bool {
        if(($user = $this->checkLogin($usr, $pwd)) !== null && $user->activo) {
            $user->authenticated = true;
            $_SESSION["current_user"] = $user;
            return true;
        }
        return false;
    }

}
