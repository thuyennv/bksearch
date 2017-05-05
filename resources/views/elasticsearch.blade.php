<!DOCTYPE html>
<html>
<head>
    <title>Elasticsearch</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">

    <!-- <link rel="stylesheet" href="{{Asset('css/style.css')}}"> -->
</head>
<body>
    <div class="header">
        <div>
            <img src="{{Asset('image/elastic.png')}}">
        </div>
            <div class="container">
                <div class="row">                         
                    <div id="custom-search-input">
                        <div class="input-group col-md-6">
                            <input type="text" class="  search-query form-control" placeholder="Search" />
                            <span class="input-group-btn">
                                <button class="btn btn-danger" type="button">
                                    <span class=" glyphicon glyphicon-search"></span>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    
                </div>
            </div>
            <form role="search" method="get" class="form search-form" action="/index.php">
  <div class="input-group">
     <input name="s" type="text" class="form-control" placeholder="Søk i Journalen">
       <span class="input-group-btn">
          <button type="submit" value="Search" class="btn btn-danger" type="button"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;</button>
       </span>
  </div>
</form>
    </div>
</body>
</html>