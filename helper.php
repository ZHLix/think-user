<?php

if (!function_exists('encrypted_data')) {
    /**
     * 获取加密字段数据
     *
     * @param string $type
     * @param bool   $decode
     *
     * @return mixed
     */
    function encrypted_data ($type = 'post', $decode = true)
    {
        $res = input("$type.encryptedData");

        if ($decode) $res = json_decode(rsa_decode($res), 1);

        return $res;
    }

}