<?php

class User extends MyDataObject  {
    public $__table = TABLE_USERS;
    public $id_user;
    public $email;
    public $passwd;
    public $nom;
    public $prenom;
    public $ts;
    public $date_creation;
    public $date_last_con;
    public $actif;
    
    public $fb_fieldsToRender = array('id','login', 'password','email','langue','pays','monnaie','id_maximile','abonne');
    public $fb_userEditableFields = array('id','login', 'password','email','langue','pays','monnaie','id_maximile','abonne');

    public function table() {
      	return array(
            'id_user' => DB_DATAOBJECT_INT,
            'email'   => DB_DATAOBJECT_STR,
            'passwd'   => DB_DATAOBJECT_STR,
            'nom'   => DB_DATAOBJECT_STR,
            'prenom'   => DB_DATAOBJECT_STR,
            'ts'   => DB_DATAOBJECT_STR + DB_DATAOBJECT_DATE + DB_DATAOBJECT_TIME,
            'date_creation'   => DB_DATAOBJECT_STR + DB_DATAOBJECT_DATE + DB_DATAOBJECT_TIME,
            'date_last_con'   => DB_DATAOBJECT_STR + DB_DATAOBJECT_DATE + DB_DATAOBJECT_TIME,
            'actif' => DB_DATAOBJECT_INT,
        );
    }
    


    /**
     * @return the $email
     */
    public function getEmail() {
    	return $this->email;
    }

    /**
     * @return the $passwd
     */
    public function getPassword() {
    	return $this->passwd;
    }

    
    /**
     * @return the $id_client
     */
    public function getIdUser() {
        return $this->id_user;
    }
    
    public function pseudo(){
        return strtolower_fr($this->prenom . substr($this->nom, 0, 1));
    }
    
    public function getName() {
        return $this->nom.' '.$this->prenom;
    }
    
    public function getShortName() {
        return substr($this->prenom, 0, 1) . ". " . $this->nom;
    }
    
    public static function getUserById($id_client) {
        $client = new User();
        $client->whereAdd("id_user = '".$id_client."'");
        if ($client->find()) {
            $client->fetch();
            return $client;
        }
        else return false;
    }
    
    public static function getUserByEmail($email) {
        $client = new User();
        $client->whereAdd("email = '".$email."'");
        if ($client->find()) {
            $client->fetch();
    
            return $client;
        }
        else return false;
    }
    
    public static function getEmailStatic($id_client){
        $client = new User();
        $client->whereAdd("id_client = '".$id_client."'");
        if ($client->find()) {
            $client->fetch();
            	
            return $client->email;
        }
        else return false;
    }
    
    public static function existsStatic($email) {
        $client_existe = new User();
        $client_existe->whereAdd("email = '".$email."'");
        if ($client_existe->find()) {
            return true;
        }
        else return false;
    }
    
    static public function createNewClientStatic($email, $password, $nom, $prenom, $pays = "fr", $options = 0, $partenaire = 0, $id_maximile = "", $optin_partner = 0) {
        global $errors, $langue, $monnaie, $pays_get, $tr;
    
        $client = new User();
        $client->nom = $nom;
        $client->prenom = $prenom;
        $client->passwd = $password;
        $client->email = $email;
        $client->date_creation = date("YmdHis");
        $client->insert();
        $client->id_user = $client->getLastInsertId();
    
        return $client;
    }
    
    public function getDateCreation() {
        return substr($this->date_creation,8,2)."/".substr($this->date_creation,5,2)."/".substr($this->date_creation,0,4);
    }
    
    public function sendEmail($type) {
        if(Tools::isValidEmail($this->getEmail())) {
            switch ($type) {
                case NEW_ACCOUNT:
                    $subject = $this->getEmailMessage("mail_inscription_user_subject");
                    $message = $this->getEmailMessage("mail_inscription_user_","txt");
                    $message_html = $this->getEmailMessage("mail_inscription_user_");
                    break;
    
                case LOST_PASSWORD:
                    $subject = $this->getEmailMessage("mail_perte_identifiants_subject");
                    $message = $this->getEmailMessage("mail_perte_identifiants_","txt");
                    $message_html = $this->getEmailMessage("mail_perte_identifiants_");
                    break;
    
                default:
                    break;
            }
            Tools::sendEmail(SENDER_EMAIL, $this->getEmail(), $subject, $message, $message_html);
        }
    }
    
    static public function sendEmailStatic($id_client, $type) {
        $client = new User();
        $client->whereAdd("id_user = '" . $id_client . "'");
        if($client->find()) {
            $client->fetch();
            $client->sendEmail($type);
        }
    }
    
	function keys() {
        return array('id_user');
    }
}