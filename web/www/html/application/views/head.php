<!DOCTYPE html>
        <html>
            <head>
                <meta charset="utf-8"/>
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <!-- Bootstrap -->
                <link href="/static/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
                <style>
                    body{
                        padding-top:60px;
                    }
                </style>
                <link href="/static/lib/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
                		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
            </head>
            <body>
                <div class="navbar navbar-fixed-top navbar-inverse">
                  <div class="navbar-inner">
                    <div class="container-fluid">

                      <!-- .btn-navbar is used as the toggle for collapsed navbar content -->
                      <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                      </a>

                      <!-- Be sure to leave the brand out there if you want it shown -->
                      <a class="brand" href="/index.php/ranking/index">ECI</a>

                      <!-- Everything you want hidden at 940px or less, place within here -->
                      <div class="nav-collapse collapse">
                            <ul class="nav">
                            <?php
                            foreach($topics as $entry){
                              if($id == $entry->id){
                            ?>
                                <li class="active"><a href="/index.php/base/get/<?=$entry->id?>"><?=$entry->title?></a></li>
                            <?php
                            }else{
                            ?>
                              <li><a href="/index.php/base/get/<?=$entry->id?>"><?=$entry->title?></a></li>
                            <?php
                              }
                            }
                            ?>
                            </ul>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="container-fluid">
                    <div class="row-fluid">
