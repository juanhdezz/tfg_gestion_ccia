<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;


class Libro extends BaseModel
{
    protected $table = 'libro';
    protected $primaryKey = 'id_libro';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'titulo',
        'isbn',
        'num_paginas',
        'autor',
        'editorial',
        'edicion',
        'year',
        'portada',
        'website'
    ];

    /**
     * Obtiene todas las solicitudes de libros para asignaturas asociadas a este libro
     */
    public function solicitudesAsignatura()
    {
        return $this->hasMany(LibroAsignatura::class, 'id_libro');
    }

    /**
     * Obtiene todas las solicitudes de libros para grupos de investigación asociadas a este libro
     */
    public function solicitudesGrupo()
    {
        return $this->hasMany(LibroGrupo::class, 'id_libro');
    }

    /**
     * Obtiene todas las solicitudes de libros para proyectos asociadas a este libro
     */
    public function solicitudesProyecto()
    {
        return $this->hasMany(LibroProyecto::class, 'id_libro');
    }

    /**
     * Obtiene todas las solicitudes de libros para posgrados asociadas a este libro
     */
    public function solicitudesPosgrado()
    {
        return $this->hasMany(LibroPosgrado::class, 'id_libro');
    }

    /**
     * Obtiene todas las solicitudes de libros para otros conceptos asociadas a este libro
     */
    public function solicitudesOtro()
    {
        return $this->hasMany(LibroOtro::class, 'id_libro');
    }

    /**
     * Obtiene todas las solicitudes pendientes de este libro
     */
    public function solicitudesPendientes()
    {
        $asignaturas = $this->solicitudesAsignatura()->where('estado', 'Pendiente Aceptación')->get();
        $grupos = $this->solicitudesGrupo()->where('estado', 'Pendiente Aceptación')->get();
        $proyectos = $this->solicitudesProyecto()->where('estado', 'Pendiente Aceptación')->get();
        $posgrados = $this->solicitudesPosgrado()->where('estado', 'Pendiente Aceptación')->get();
        $otros = $this->solicitudesOtro()->where('estado', 'Pendiente Aceptación')->get();

        // Combinar todas las colecciones
        return $asignaturas
            ->concat($grupos)
            ->concat($proyectos)
            ->concat($posgrados)
            ->concat($otros);
    }

    /**
     * Verifica si un usuario ha solicitado este libro recientemente
     * (puede usarse para evitar solicitudes duplicadas)
     */
    public function solicitadoRecientementePor($idUsuario)
    {
        $fechaLimite = now()->subDays(30); // Por ejemplo, en los últimos 30 días
        
        return $this->solicitudesAsignatura()
                ->where('id_usuario', $idUsuario)
                ->where('fecha_solicitud', '>=', $fechaLimite)
                ->exists()
            || $this->solicitudesGrupo()
                ->where('id_usuario', $idUsuario)
                ->where('fecha_solicitud', '>=', $fechaLimite)
                ->exists()
            || $this->solicitudesProyecto()
                ->where('id_usuario', $idUsuario)
                ->where('fecha_solicitud', '>=', $fechaLimite)
                ->exists()
            || $this->solicitudesPosgrado()
                ->where('id_usuario', $idUsuario)
                ->where('fecha_solicitud', '>=', $fechaLimite)
                ->exists()
            || $this->solicitudesOtro()
                ->where('id_usuario', $idUsuario)
                ->where('fecha_solicitud', '>=', $fechaLimite)
                ->exists();
    }
}