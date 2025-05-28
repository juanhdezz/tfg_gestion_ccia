<?php

it('devuelve una respuesta exitosa', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});
