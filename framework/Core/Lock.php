<?php

/* !
 * SmoothPHP
 * This file is part of the SmoothPHP project.
 * * * *
 * Copyright (C) 2016 Rens Rikkerink
 * License: https://github.com/Ikkerens/SmoothPHP/blob/master/License.md
 * * * *
 * Lock.php
 * Abstract configuration for SmoothPHP
 */

namespace SmoothPHP\Framework\Core;

use SmoothPHP\Framework\Cache\CacheProvider;

class Lock {
    private $key;
    private $handle;
    private $owned;

    public function __construct( $key ) {
        $this->key = $key;

        $dirname = __ROOT__ . 'cache/locks/';
        if ( !is_dir( $dirname ) )
            mkdir( $dirname, CacheProvider::PERMS, true );
    }

    public function lock( $waitForRelease = true ) {
        $this->handle = fopen( sprintf( '%scache/locks/%s.lock', __ROOT__, $this->key ), 'c+' );
        $this->owned = flock( $this->handle, LOCK_EX | LOCK_NB, $waitForRelease );

        if ( !$this->owned && $waitForRelease )
            flock( $this->handle, LOCK_EX, $waitForRelease );

        return $this->owned;
    }

    public function unlock() {
        if ( !$this->owned )
            throw new \RuntimeException( 'Attempting to unlock a lock that is not owned by this process.' );

        flock( $this->handle, LOCK_UN );
        fclose( $this->handle );
        unlink( sprintf( '%scache/locks/%s.lock', __ROOT__, $this->key ) );
    }
}