<?php

require 'template/bootstrap.php';

if (isset($_GET['id']) && isset($_GET['token'])){

    $auth = App::getAuth();

    $db = App::getDatabase();

    $user = $auth->checkRestToken($db,$_GET['id'],$_GET['token']);

    if($user)
    {
        if (!empty($_POST))
        {

            $validator = new Validator($_POST);

            $validator->isConfirmed('password');

            if($validator->isValid())
            {

                $password = $auth->hashPassword($_POST['password']);

                $db->query("UPDATE PARRAIN SET password = ?, reset_at = NULL , reset_token = NULL WHERE id = ?",[$password,$_GET['id']]);


                Session::getInstance()->setFlash('success',"Votre mot de passe a été bien modifié");

                $auth->connect($user);

                App::redirect('account');


            }

        }

    }
    else{

     Session::getInstance()->setFlash('danger',"Ce token n'est pas valide");

     App::redirect('connexion');


}
}
else
{
    Session::getInstance()->setFlash('danger',"Ce token n'est pas valide");

    App::redirect('connexion');

}

require 'template/header.php';

?>



    <h1>Réinitilisation de mot de passe</h1>

    <form action="" method="POST" >

        <div class="form-group">
            <label>Mot de passe </label>
            <input type="password" name="password" placeholder="votre nouveau mot de passe" class="form-control" >
        </div>



        <div class="form-group">
            <label>Confirmation de Mot de passe</label>
            <input type="password" name="confirmation" placeholder="Confirmation de votre mot de passe" class="form-control" >
        </div>



        <button text="submit" class="btn btn-success">Réinitialiser</button>

    </form>


<?php require 'template/footer.php';?>