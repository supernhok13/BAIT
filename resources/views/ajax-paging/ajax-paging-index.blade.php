<div class="books-container">
    @foreach($books as $book)
    <div class="col-sm-3 item-book-col">
        <div class="item-book item-all-book">
            <div class="head-item">
                <div class="borrow-button" data-id="{{$book->id}}" data-name="{{$book->name}}" data-author="{{$book->author}}" data-image="{{($book->image==null) ? "images/convallis-pharetra.jpg": "book_image/".$book->image}}">
                    Quan tâm sách
                </div>
                <div class="item-book-image">
                    <a href="b/{{$book->id}}/{{$book->url_book}}" class="link-to-book">
                        @if($book->image==null)
                            <img class="book-image-sec" src="images/convallis-pharetra.jpg" alt="">
                        @else
                            <img class="book-image-sec" src="book_image/{{$book->image}}" alt="" width="323" height="369">
                        @endif
                    </a>
                </div>
            </div>
            <div class="book-title">
                <a href="b/{{$book->id}}/{{$book->url_book}}">{{$book->name}}</a>
                @if($book->available>0)
                    <p class="available-book book-status">
                        Còn sách
                    </p>
                @else
                    <p class="non-available-book book-status">
                        Hết sách
                    </p>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>

@include("ajax-paging.paging-link",["paginator"=>$books])