<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once __DIR__ . '/../../helpers/vars.php';
include_once __DIR__ . '/../../helpers/db.php';
include_once __DIR__ . '/../permiso/model.php';

use \De\Set as Set;

class Perfil extends Model
{
    private static $permisos;
    private static $tbl_perfil_tiene_permiso = null;

    public function __construct(array $config = [])
    {
        parent::__construct('perfil', config: $config);
        if(self::$permisos === null) {
            self::$permisos = new Permiso($config);
        }
        if(self::$tbl_perfil_tiene_permiso ===  null) {
            self::$tbl_perfil_tiene_permiso = New Table("perfil_tiene_permiso", $config);
        }
    }

    public function __tostring(): string
    {
        return $this->nombre ?? "Perfil";
    }

    public function getAll(): array
    {
        $data = parent::getAll();
        uasort($data, function ($a, $b) {
            return strcmp($a['nombre'], $b['nombre']);
        });
        return $data;
    }

    public function save(): bool {
        if(parent::save() || $this->pk)
        {
            self::$tbl_perfil_tiene_permiso->delete("perfil_id = ?", [$this->pk]);
            foreach(getvar("permisos") as $perm) {
                self::$tbl_perfil_tiene_permiso->insert(["perfil_id" => $this->pk, "permiso_id" => $perm]);
            }
            return true;
        }
        return false;
    }

    public function can($perms): bool {
        if(is_string($perms)) {
            list($tipo, $codename) = explode(".", $perms);
            if($codename === "*") {
                if (count($permisos_encontrados = self::$permisos->selectAll("tipo = ?", [$tipo])) === 0) {
                    throw new Exception("No se ha encontrado el tipo de permiso: " . $tipo);
                }
                $ids = array_column($permisos_encontrados, "id");
                $placeholders = implode(',', array_fill(0, count($ids), '?'));
                if (self::$tbl_perfil_tiene_permiso->select("perfil_id = ? and permiso_id in ($placeholders)", [$this->pk, ...$ids]) !== null) {
                    return true;
                }
            } else {
                if(($perm = self::$permisos->select("tipo = ? and codename = ?", [$tipo, $codename])) === null) {
                    throw new Exception("No se ha encontrado el permiso: " . $perms);
                }
                return self::$tbl_perfil_tiene_permiso->select("perfil_id = ? and permiso_id = ?", [$this->pk, $perm["id"]]) !== null;
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

    public function todosLosPermisos(): array   {
        return self::$permisos->getAll();
    }

}
