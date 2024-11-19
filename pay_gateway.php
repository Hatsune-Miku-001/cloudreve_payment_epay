<?php
$epay_config[ 'apiurl' ] = $_GET[ 'payment' ];
$epay_config[ 'pid' ] = $_GET[ 'pid' ];
$epay_config[ 'key' ] = $_GET[ 'key' ];
$epay_config[ 'type' ] = $_GET[ 'type' ];

if ( isset( $_SERVER[ 'HTTP_AUTHORIZATION' ] ) ) {
    $authorization = $_SERVER[ 'HTTP_AUTHORIZATION' ];
} elseif ( isset( $_SERVER[ 'REDIRECT_HTTP_AUTHORIZATION' ] ) ) {
    $authorization = $_SERVER[ 'REDIRECT_HTTP_AUTHORIZATION' ];
} else {
    echo json_encode( [ 'code' => -1, 'msg' => '未找到签名' ] );
    exit();
}

$data = json_decode( file_get_contents( 'php://input' ), true );

if ( !parseBearerString( $authorization, file_get_contents( 'php://input' ), getallheaders() )[ 'isValid' ] ) {
    echo json_encode( [ 'code' => -1, 'msg' => '签名验证错误' ] );
    exit();
}

$response = createYipayOrder( $data[ 'order_no' ], $data[ 'amount' ]/100, $data[ 'name' ], $data[ 'notify_url' ], $epay_config[ 'apiurl' ], $epay_config[ 'pid' ], $epay_config[ 'key' ], $epay_config[ 'type' ] );

if ( $response[ 'status' ] ) {
    echo json_encode( [
        'code' => 0,
        'data' => $response[ 'massage' ]
    ] );
} else {
    echo json_encode( [
        'code' => -1,
        'msg' => '支付网关调用失败'
    ] );
    exit();
}

function createYipayOrder( $trade_no, $total_fee, $param, $turl, $e_url, $e_pid, $e_key, $e_type ) {
    $return = [
        'status' => false,
        'massage' => '请求失败'
    ];
    $params = [
        'pid' => $e_pid,
        'type' => $e_type,
        'out_trade_no' => $trade_no,
        'notify_url' => $turl,
        'return_url' => 'https://api.lucloud.top/api/v2/payment/return_url.php',
        'name' => $param,
        'money' => $total_fee,
        'sitename' => 'Starry Cloud - 星云网盘',
    ];

    ksort( $params );
    $sign_string = urldecode( http_build_query( $params ) ) . $e_key;

    $sign = md5( $sign_string );

    $params[ 'sign' ] = $sign;
    $params[ 'sign_type' ] = 'MD5';

    $ch = curl_init();

    curl_setopt( $ch, CURLOPT_URL, $e_url );
    curl_setopt( $ch, CURLOPT_POST, true );
    curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $params ) );

    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

    $response = curl_exec( $ch );

    if ( curl_errno( $ch ) ) {
        $return[ 'massage' ] = url_error( $ch );
    } else {
        $response = json_decode( $response, true );
        $return[ 'massage' ] = $response[ 'qrcode' ];
        $return[ 'status' ] = true;
    }

    curl_close( $ch );

    return $return;
}

function parseBearerString( $input, $body, $headers ) {
    $result = [
        'isValid' => false,
        'message' => ''
    ];

    if ( preg_match( '/^Bearer\s+([^\:]+):(\d+)$/', $input, $matches ) ) {
        $signature = $matches[ 1 ];
        $timestamp = ( int )$matches[ 2 ];
        $currentTimestamp = time();

        if ( $timestamp > $currentTimestamp ) {
            $result[ 'isValid' ] = true;
            $result[ 'message' ] = '签名有效';
        } else {
            $result[ 'message' ] = '时间戳无效';
        }
    } else {
        $result[ 'message' ] = '输入格式不正确';
    }

    return $result;
}
?>
