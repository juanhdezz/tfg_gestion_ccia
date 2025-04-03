<?php

namespace App\Traits;

trait SolicitudLibroTrait
{
    /**
     * Verifica si esta solicitud está pendiente de aceptación
     */
    public function estaPendiente()
    {
        return $this->estado === 'Pendiente Aceptación';
    }

    /**
     * Verifica si esta solicitud ha sido aceptada
     */
    public function estaAceptada()
    {
        return $this->estado === 'Aceptado';
    }

    /**
     * Verifica si esta solicitud ha sido denegada
     */
    public function estaDenegada()
    {
        return $this->estado === 'Denegado';
    }

    /**
     * Verifica si esta solicitud ha sido pedida
     */
    public function estaPedida()
    {
        return $this->estado === 'Pedido';
    }

    /**
     * Verifica si esta solicitud ha sido recibida
     */
    public function estaRecibida()
    {
        return $this->estado === 'Recibido';
    }

    /**
     * Verifica si esta solicitud está en la biblioteca
     */
    public function estaEnBiblioteca()
    {
        return $this->estado === 'Biblioteca';
    }

    /**
     * Verifica si esta solicitud está agotada o descatalogada
     */
    public function estaAgotadaODescatalogada()
    {
        return $this->estado === 'Agotado/Descatalogado';
    }

    /**
     * Acepta esta solicitud
     */
    public function aceptar($observaciones = null)
    {
        $this->estado = 'Aceptado';
        $this->fecha_aceptado_denegado = now();
        if ($observaciones) {
            $this->observaciones = $observaciones;
        }
        return $this->save();
    }

    /**
     * Deniega esta solicitud
     */
    public function denegar($observaciones = null)
    {
        $this->estado = 'Denegado';
        $this->fecha_aceptado_denegado = now();
        if ($observaciones) {
            $this->observaciones = $observaciones;
        }
        return $this->save();
    }

    /**
     * Marca esta solicitud como pedida
     */
    public function marcarComoPedida($observaciones = null)
    {
        $this->estado = 'Pedido';
        $this->fecha_pedido = now();
        if ($observaciones) {
            $this->observaciones = $observaciones;
        }
        return $this->save();
    }

    /**
     * Marca esta solicitud como recibida
     */
    public function marcarComoRecibida($observaciones = null)
    {
        $this->estado = 'Recibido';
        $this->fecha_recepcion = now();
        if ($observaciones) {
            $this->observaciones = $observaciones;
        }
        return $this->save();
    }

    /**
     * Marca esta solicitud como enviada a la biblioteca
     */
    public function marcarComoEnBiblioteca($observaciones = null)
    {
        $this->estado = 'Biblioteca';
        if ($observaciones) {
            $this->observaciones = $observaciones;
        }
        return $this->save();
    }

    /**
     * Marca esta solicitud como agotada o descatalogada
     */
    public function marcarComoAgotadaODescatalogada($observaciones = null)
    {
        $this->estado = 'Agotado/Descatalogado';
        if ($observaciones) {
            $this->observaciones = $observaciones;
        }
        return $this->save();
    }
}