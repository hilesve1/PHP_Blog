<!DOCTYPE html>
<html lang="ru">
<head>
    <?php 
        require_once 'mysql_connect.php';
        $sql = 'SELECT * FROM `articles` WHERE `id` =:id';
        $query = $pdo->prepare($sql);
        $query->execute(['id' => $_GET['id']]);

        $article = $query->fetch(PDO::FETCH_OBJ);
      $site_title = $article->title;
      require 'blocks/head.php' 
    ?>
</head>
<body >
  <?php require 'blocks/header.php'; ?>
  <main class="container mt-5">
    <div class="row">
      <div class="col-md-8">
          <div class="jumbotron">
              <h1><?=$article->title?></h1>
              <p><b>Автор Статьи:</b> <mark><?=$article->author?></mark></p>
              <?php
                 $date = date('d', $article->date);
                $array = [".1", ".2", ".3", ".4", ".5", ".6", ".7", ".8", ".9", ".10", ".11", ".12"];
                $date .= $array[date('n' ,  $article->date) - 1];
                $date .= date(' H:i', $article->date);
              ?>
              <p><b>Время публикации</b> <u><?=$date?></u></p>
              <p><?=$article->intro?>
              <br><br>
              <?=$article->text?>
            </p>
            <?php
             if($article->author == $_COOKIE['log']):
           ?>
             <a href="/changing.php">Изменить</a>
            <?php
            endif;
        ?>
          </div>
          
          <h3 class="mt-5">Комментарии</h3>
          <?php
             if(isset($_COOKIE['log'])):
           ?>
        <form action="/news.php?id=<?=$_GET['id']?>" method="post">
        <label for="username">Ваше Имя</label>
        <input type="text" name="username" value="<?=$_COOKIE['log']?>" id="username" class="form-control">

        <label for="mess">Сообщение</label>
        <textarea maxlength="9" type="mess" name="mess" id="mess" class="form-control"></textarea>

        <div class="alert alert-danger mt-2" id="errorBlock"></div>
       
        <button type="submit" id="mess_send" class="btn btn-warning mt-5 mb-2">Добавить комментарий</button>
        </form>
        <?php
           
            if($_POST['username'] != '' && $_POST['mess'] != '' ){
                $username = trim(filter_var($_POST['username'], FILTER_SANITIZE_STRING));
                $mess = trim(filter_var($_POST['mess'], FILTER_SANITIZE_STRING));

                $sql = 'INSERT INTO comments(name, mess, article_id) VALUES(?, ?, ?)';
                $query = $pdo->prepare($sql);
                $query->execute([$username, $mess, $_GET['id']]); 
            }

            $sql = 'SELECT * FROM `comments` WHERE `article_id` = :id ORDER BY `id` DESC';
            $query = $pdo->prepare($sql);
            $query->execute(['id' => $_GET['id']]); 
            $comments = $query->fetchAll(PDO::FETCH_OBJ);

            foreach ($comments as $comment){
                echo "<div class='alert alert-info mb-2'>
                    <h4>$comment->name</h4>
                    <p>$comment->mess</p>
                </div>";
            }
        ?>
      </div>
      <?php
            else:
        ?>
          <h1>Чтобы оставить комментарии зарегистрируйтесь!</h1>
        <?php
            endif;
        ?>
      <?php require 'blocks/aside.php'; ?>
    </div>
  </main>
  <?php require 'blocks/footer.php'; ?>
</body>
</body>
</html>