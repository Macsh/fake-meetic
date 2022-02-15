<?php

class MyDatabase {

    private $login;
    private $password;
    private $connect;
    public $dbname = 'amitic';

    public function __construct($login='**username**', $password='**password**'){
        $this->login = $login;
        $this->password = $password;
        $this->connect_to_db();
    }
    
    function connect_to_db() {
        try {
            $db = new PDO('mysql:host=localhost;dbname='.$this->dbname.';charset=utf8',$this->login,
            $this->password,
        );
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        $this->connect = $db;
        }
        catch (Exception $e){
            die("Connection failed: " . $e->getMessage());
        }
    }


    public function add_user_to_db($data, $hobbies, $pw) {
        $sql = "INSERT INTO user(firstname, lastname, birthdate, email, gender, city, active) VALUES(:firstname, :lastname, :birthdate, :mail, :gender, :city, 1)";
        $id = "SELECT MAX(id) as id FROM user";
        $checkUser = "SELECT email FROM user WHERE email LIKE '%{$data['mail']}%'";

        $checkPrepare = $this->connect->prepare($checkUser);
        $checkPrepare->execute();
        $getCheck = $checkPrepare->fetch();

        if($getCheck != ""){
            echo "Mail déjà utilisé.";
        }
        else if($getCheck == ""){
            $insert = $this->connect->prepare($sql);
            $insert->execute($data);

            $executeId = $this->connect->prepare($id);
            $executeId->execute();
            $get_id = $executeId->fetch();
            $lastId = $get_id->id;

            for($i=0;$i<count($hobbies);$i++){
                $hobbiesSQL = "INSERT INTO user_hobby(user_id, hobbies_id) VALUES($lastId, $hobbies[$i])";
                $insertHobby = $this->connect->prepare($hobbiesSQL);
                $insertHobby->execute();
            };

            $pwSQL = "INSERT INTO password(password, user_id) VALUES('$pw', '$lastId')";
            $insertPW = $this->connect->prepare($pwSQL);
            $insertPW->execute();
            echo "Inscription réussie";
        }
    }

    public function do_user_exists($mail, $pw) {
        $sql = "SELECT * FROM user WHERE email LIKE '$mail'";
        $check = $this->connect->prepare($sql);
        $check->execute();
        $results = $check->fetch();
        if(empty($mail)){
            echo "Veuillez renseigner un mail.";
        }
        elseif($results == false){
            echo "Cet utilisateur n'existe pas ou mauvais mail renseigné.";
        }
        else {
            $id = "SELECT id FROM user WHERE email LIKE '$mail'";
            $executeId = $this->connect->prepare($id);
            $executeId->execute();
            $get_id = $executeId->fetch();
            $userId = $get_id->id;
            $savedPW = "SELECT password FROM password WHERE user_id = '$userId'";
            $executePW = $this->connect->prepare($savedPW);
            $executePW->execute();
            $getPW = $executePW->fetch();
            $dbPW = $getPW->password;
            if (password_verify($pw, $dbPW)){
                if($results->active == 0){
                    echo "Compte désactivé?id=".$userId;
                }
                else {
                    $_SESSION['currentuser'] = $userId;
                    echo $userId;
                }
            }
            else {
                echo "Les mots de passe ne correspondent pas.";
            }
        }
    }

    public function edit_user($data, $userId, $old_password, $pw){
        $savedPW = "SELECT password FROM password WHERE user_id = '$userId'";
        $executePW = $this->connect->prepare($savedPW);
        $executePW->execute();
        $getPW = $executePW->fetch();
        $dbPW = $getPW->password;
        if (password_verify($old_password, $dbPW)){
            if($pw != ''){
                $changePwSql = "UPDATE password SET password = '$pw' WHERE user_id = '$userId'";
                $editPW = $this->connect->prepare($changePwSql);
                $editPW->execute();
            }
            $editUser = "UPDATE user SET firstname = :firstname, lastname = :lastname, email = :mail, city = :city WHERE id = '$userId'";
            $edit = $this->connect->prepare($editUser);
            $edit->execute($data);
            if($pw != ''){
                echo "Vos données et mot de passe ont bien été mis à jour.";
            }
            else {
                echo "Vos données ont bien été mises à jour.";
            }
        }
        else {
            echo "Votre ancien mot de passe ne correspond pas.";
        }
    }

    function get_user_data($id){
        $data=[];
        $sql = "SELECT *, date(birthdate) as birthdate FROM user WHERE id = '$id'";
        $check = $this->connect->prepare($sql);
        $check->execute();
        $results = $check->fetch();
        if($results->active == 0){
            $data['active'] = 0;
        }
        else {
            $sqlHobbies = "SELECT hobby FROM hobbies JOIN user_hobby ON hobbies.id = user_hobby.hobbies_id WHERE user_hobby.user_id = '$id'";
            $checkHobbies = $this->connect->prepare($sqlHobbies);
            $checkHobbies->execute();
            $resultsHobbies = $checkHobbies->fetchAll();
            $data['firstname'] = $results->firstname;
            $data['lastname'] = $results->lastname;
            $data['email'] = $results->email;
            $data['birthdate'] = $results->birthdate;
            $data['gender'] = $results->gender;
            $data['city'] = $results->city;
            $data['hobbies'] = $resultsHobbies;
            if($id == $_SESSION['currentuser']){
                $data['access'] = 1;
            }
            else {
                $data['access'] = 0;
            }
        }
        
        echo json_encode($data);
    }

    function deactivate_user($id){
        $sql = "UPDATE user SET active = 0 WHERE id = '$id'";
        $deactivate = $this->connect->prepare($sql);
        $deactivate->execute();
        echo "Votre compte a bien été désactivé";
    }

    function reactivate_user($id){
        $sql = "UPDATE user SET active = 1 WHERE id = '$id'";
        $reactivate = $this->connect->prepare($sql);
        $reactivate->execute();
        echo "Votre compte a bien été réactivé";
    }

    function get_members($datas){
        $data = [];
        $sql = "SELECT *, date(birthdate) as birthdate FROM user JOIN user_hobby ON user_hobby.user_id = user.id JOIN hobbies ON user_hobby.hobbies_id = hobbies.id WHERE gender LIKE '%{$datas['gender']}%' {$datas['city']} {$datas['hobbies']} AND birthdate BETWEEN '{$datas['ageBef']}' AND '{$datas['ageAft']}' {$datas['id']} {$datas['userid']} GROUP BY email ORDER BY RAND()";
        $getMembers = $this->connect->prepare($sql);
        $getMembers->execute();
        $results = $getMembers->fetch();
        if($results == false){
            $data['noresult'] = 0;
        }
        else{
            $userId = $results->user_id;
            $sqlHobbies = "SELECT hobby from hobbies JOIN user_hobby ON user_hobby.hobbies_id = hobbies.id WHERE user_hobby.user_id = '$userId'";
            $getHobbies = $this->connect->prepare($sqlHobbies);
            $getHobbies->execute();
            $resultsHobbies = $getHobbies->fetchAll();
            $data['firstname'] = $results->firstname;
            $data['lastname'] = $results->lastname;
            $data['email'] = $results->email;
            $data['birthdate'] = $results->birthdate;
            $data['gender'] = $results->gender;
            $data['city'] = $results->city;
            $data['id'] = $userId;
            $data['hobbies'] = $resultsHobbies;
        }
        echo json_encode($data);
    }
}
?>