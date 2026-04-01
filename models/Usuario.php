<?php
class Usuario extends Model {
    protected static $table = 'usuarios';
    protected static $fillable = ['nombre', 'email', 'password', 'rol', 'activo'];

    public static function findByEmail($email) {
        return self::findOne('email = ?', [$email]);
    }

    public static function authenticate($email, $password) {
        $user = self::findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}
