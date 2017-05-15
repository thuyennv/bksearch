<!DOCTYPE html>
<html>
<head>
	<title>Search Form</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="{{Asset('css/style.css')}}">
</head>
<body>
	<div class="container">
        <div class="row search-form">        
            <div class="col-md-12">
                <img src="{{Asset('image/elastic.png')}}">
                <div class="input-group" id="adv-search">
                        <input type="text" class="form-control" placeholder="Search for snippets" style="height: 50px" value="{{  old('name', isset($key) ? $key : null) }}"/>
                        <div class="input-group-btn">
                            <div class="btn-group" role="group">
                                <div class="dropdown dropdown-lg">
                                    <button id="btn-submit" type="submit" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false" style="height: 50px; width: 50px;"><span class="caret"></span></button>
                                    <div class="dropdown-menu dropdown-menu-right" role="menu">
                                        <form class="form-horizontal" role="form" action="" method="GET" >
                                            <div class="form-group">
                                                <label for="filter">Filter by</label>
                                                <select class="form-control" name="search_param">
                                                    <option value="title" selected>Title</option>
                                                    <option value="content">Content</option>
                                                    <option value="all">All</option>
                                                </select>
                                            </div>
<div class="form-group">
                                                <label for="filter">Filter by</label>
                                                <select class="form-control" name="search_param">
                                                    <option value="title" selected>Title</option>
                                                    <option value="content">Content</option>
                                                    <option value="all">All</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="contain"><input type="radio" name="optradio" value="1">&nbsp Full text</label>
                                                <input class="form-control" type="text" name="key_full_text" value="{{  old('key_full_text', isset($key_full_text) ? $key_full_text : null) }}"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="contain"><input type="radio" name="optradio" value="2">&nbsp Contains one of the words</label>
                                                <input class="form-control" type="text" name="key_one_word" value="{{  old('key_one_word', isset($key_one_word) ? $key_one_word : null) }}"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="contain"><input type="radio" name="optradio" value="3" checked>&nbsp Contains the words</label>
                                                <input class="form-control" type="text" name="key_all_word" value="{{  old('key_all_word', isset($key_all_word) ? $key_all_word : null) }}" />
                                            </div>
                                            <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>                                        
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary" style="height: 50px; width: 60px;" ><span class="glyphicon glyphicon-search" aria-hidden="true" ></span></button>
                            </div>
                        </div>
                     </form>
                </div>
              </div>
              </form>
            </div>
        <div class="row"> 
            @if (!empty($results))  
                <div class="result col-md-6">
                        <p>Tổng {{ $total}}</p>
                        @foreach ($results as $result)
                            <h3>{{ $result['_source']["title"] }}</h3>
                            <p><a href="{{ $result['_source']['url'] }}">{{ $result['_source']['url'] }}</a></p>
                            @if(isset($result['highlight']))
                                <p>... {!! isset($result['highlight']['content']) ? $result['highlight']['content'][0] : $result['highlight']['title'][0] !!} ...</p>
                            @endif
                            <hr class="hr-result">
                        @endforeach                        
                        {{ $results->links() }}
                </div>
            @endif
        </div>
	</div>
       <script src = "{{Asset('js/jquery.min.js')}}"></script>
      <script src="{{Asset('js/bootstrap.min.js')}}" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
      <script type="text/javascript">
          
            $(document).ready(function(e){
                $('.search-panel .dropdown-menu').find('a').click(function(e) {
                    e.preventDefault();
                    var param = $(this).attr("href").replace("#","");
                    var concept = $(this).text();
                    $('.search-panel span#search_concept').text(concept);
                    $('.input-group #search_param').val(param);
                });

                var key_all_word = $('input[name=key_all_word]').val();
                var key_one_word = $('input[name=key_one_word]').val();
                var key_full_text = $('input[name=key_full_text]').val();

                if (key_all_word){
                    $("input[type=radio][value=3]").attr('checked',true);
                    $('input[name=key_full_text]').attr('disabled', true);
                    $('input[name=key_one_word]').attr('disabled', true);
                    $('input[name=key_all_word]').attr('disabled', false);
                } else if (key_full_text){
                    $("input[type=radio][value=1]").attr('checked',true);
                    $('input[name=key_full_text]').attr('disabled', false);
                    $('input[name=key_one_word]').attr('disabled', true);
                    $('input[name=key_all_word]').attr('disabled', true);
                } else {
                    $("input[type=radio][value=2]").attr('checked',true);
                    $('input[name=key_full_text]').attr('disabled', true);
                    $('input[name=key_one_word]').attr('disabled', false);
                    $('input[name=key_all_word]').attr('disabled', true);
                }

                $("input[type=radio]").click(function() {
                    if($(this).attr("value")=="1") {
                        $('input[name=key_full_text]').attr('disabled', false);
                        $('input[name=key_one_word]').attr('disabled', true);
                        $('input[name=key_all_word]').attr('disabled', true);
                    } else if($(this).attr("value")=="2") {
                        $('input[name=key_full_text]').attr('disabled', true);
                        $('input[name=key_one_word]').attr('disabled', false);
                        $('input[name=key_all_word]').attr('disabled', true);
                    } else {
                        $('input[name=key_full_text]').attr('disabled', true);
                        $('input[name=key_one_word]').attr('disabled', true);
                        $('input[name=key_all_word]').attr('disabled', false);
                    }
                });
            });

      </script>
</body>
</html>
