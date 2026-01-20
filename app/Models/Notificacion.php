<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    protected $table = 'notificaciones';

    protected $fillable = [
        'user_id',
        'tipo',
        'titulo',
        'mensaje',
        'icono',
        'url',
        'leida',
        'data'
    ];

    protected $casts = [
        'leida' => 'boolean',
        'data' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getByUser($userId, $limit = 20)
    {
        return self::where('user_id', $userId)
                   ->orderBy('created_at', 'DESC')
                   ->limit($limit)
                   ->get();
    }

    public static function countUnread($userId)
    {
        return self::where('user_id', $userId)
                   ->where('leida', false)
                   ->count();
    }

    public static function crearNotificacion($data)
    {
        return self::create($data);
    }

    public function markAsRead()
    {
        $this->leida = true;
        $this->save();
    }

    public static function markAllAsRead($userId)
    {
        return self::where('user_id', $userId)
                   ->update(['leida' => true]);
    }
}
