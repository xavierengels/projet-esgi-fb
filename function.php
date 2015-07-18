<?php
/**
 * Created by PhpStorm.
 * User: xavierengels
 * Date: 18/07/15
 * Time: 15:46
 */

function getAlbums($session, $id){
    $request = new FacebookRequest($session, 'GET', '/' . $id . '/albums');
    $response = $request->execute();
    $albums = $response->getGraphObject();

    return $albums;
}