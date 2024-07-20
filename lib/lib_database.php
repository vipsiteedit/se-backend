<?php

function se_db_dsn( $dsn = 'mysql' ) {
    $dsnarr = array( 'mysql', 'pgsql' );
    if ( in_array( $dsn, $dsnarr ) )
    require_once dirname( __file__ ) . '/database/se_db_' . $dsn . '.php';
}
