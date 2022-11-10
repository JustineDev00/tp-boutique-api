<?php

namespace Helpers;

use Exception;

class Token
{
    private static $prefix = "$2y$08$"; //bcrypt (salt = 8) //va servir au décodage
    private static $defaultValidity = 60 * 60 * 1; //1h
    private function __construct()
    {
        $args = func_get_args();
        if (empty($args)) {
            throw new Exception("one argument required");
        } elseif (is_array($args[0])) {
            //si l'argument[0] est un tableau, c'est un tableau à encoder;
            $this->encode($args[0]);
        } elseif (is_string($args[0])) {
            //si l'argument[0] est une string, c'est un token à décoder;

            $this->decode($args[0]);
        } else {
            throw new Exception("argument must be a string or an array");
        }
    }
    public array $decoded; //stocke le tableau de données
    public string $encoded; //stocke le token

    public static function create($entry): Token
    {
        return new Token($entry);
    }

    /**
     * 1. Crée un token à partir d'un tableau de données
     * 2. $decoded contient les informations à stocker dans la token
     * Si les entrées createdAt, usableAt, validity et expireAt
     * ne sont pas fournies dans $decoded, il faut les ajouter
     * 3. un token est composé d'un payload et d'une signature
     * (séparé par un caractère remarquable qui permettra un découpage)
     * Le payload est un encodage en base64 du tableau de données (stringifié)
     * La signature est égale au payload hashé en bcrypt (salt = 8)
     * Le token, une fois construit, doit être encodé pour être transmis dans un url
     */
    private function encode(array $decoded = []): void
    {
        
        // TO DO : ajouter à $decoded les entrées createdAt, usableAt, validity and expireAt si elles n'existent pas
        if(!isset($decoded["createdAt"])){
            $decoded['iat'] = time();
        }
        if(!isset($decoded["usableAt"])){
            $decoded['nbf'] = isset($decoded['createdAt']) ? $decoded['createdAt'] : time();
        }
        if(!isset($decoded["validity"])){
            $decoded['val'] = Token::$defaultValidity;
        }
        if(!isset($decoded['expireAt'])){
            $decoded['exp'] = isset($decoded['iat']) ? $decoded['iat'] + Token::$defaultValidity : time() + Token::$defaultValidity;
        }
        $this->decoded = $decoded;
        $payload = json_encode($this->decoded);
        // stringification  ===> nécessite de garder les clés + les valeurs == json_encode;
        $payload = base64_encode($payload); //payload = $decoded après stringification + encodage;
        $signature = password_hash($payload, PASSWORD_BCRYPT, ['cost' => 8]);  //signature = $payload hashé !! enlever le préfixe!!!
        $signature = str_replace(Token::$prefix, "", $signature);
        $spacer = $_ENV['jwt']['spacer'];
        $encoded = $payload . $spacer . $signature; //$payload + caractère remarquable + $signature
        $this->encoded = urlencode($encoded); //$this->encoded correspond à $encoded  => nécessité d'utiliser urlencode pour le passer dans une url
    }
    /**
     * Décode un token pour obtenir le tableau de données initial
     * (faire le cheminement de la méthode encode dans l'autre sens)
     */
    private function decode(string $encoded): void
    {
        $this->encoded = $encoded;
        $tokenString = urldecode($this->encoded);
        $spacer = $_ENV['jwt']['spacer'];
        $tokenArray = explode($spacer, $tokenString);
        $payload = $tokenArray[0];
        $signature = $tokenArray[1];
        $signature = password_verify($payload, Token::$prefix . $signature);  //vérifie que la signature correspond bien au $payload hashé
        if($signature){
            $payload = base64_decode($payload);
            $payload = json_decode($payload, true);
            $decoded = $payload;
        }
        $this->decoded = $decoded ?? null;
    }
    /**
     * Vérifie la validité du token encodé ($this->decoded not null)
     * si $withDate vaut true vérifie également les dates expireAt et usableAt
     */
    public function isValid(bool $withDate = true): bool
    {
        if (!isset($this->decoded)) {
            return false;
        }
        if ($withDate) {
            $decoded = $this->decoded;
            $expDate = $decoded['exp'];
            $usableAt = $decoded['nbf'];
            if(!isset($usableAt) || !isset($expDate) || $usableAt > time() || $expDate < time()){
                //si pas de date d'utilisation, d'expiration, que le token n'est pas encore utilisable ($usableAt est dans le futur) ou que le token a expiré ($expDate est dans le passé)
                return false;
            }
            
        }
        return true;
    }
}
