<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
      [v-cloak] {
        display: none;
      }
      .l-5{
        width:65px!important;
      }
      .l-4{
        width:60px!important;
      }
      .l-3{
        width:55px!important;
      }
    </style>
  </head>
  <body>
    <div class="container-fluid">
      <h1 class="page-header"><?php echo CHtml::encode($this->pageTitle); ?></h1>
      <?php echo $content;?>
    </div>
  </body>
</html>