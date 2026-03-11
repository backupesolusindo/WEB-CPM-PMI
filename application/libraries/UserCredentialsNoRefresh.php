<?php

namespace OAuth2\GrantType;

use OAuth2\ResponseType\AccessTokenInterface;

class UserCredentialsNoRefresh extends UserCredentials
{
    /**
     * Override createAccessToken agar refresh token tidak disertakan.
     *
     * @param AccessTokenInterface $accessToken
     * @param mixed $client_id
     * @param mixed $user_id
     * @param mixed $scope
     * @return array
     */
    public function createAccessToken(
        AccessTokenInterface $accessToken,
        $client_id,
        $user_id,
        $scope
    ) {
        $token = parent::createAccessToken($accessToken, $client_id, $user_id, $scope);
        if (isset($token['refresh_token'])) {
            unset($token['refresh_token']);
        }
        return $token;
    }
}
