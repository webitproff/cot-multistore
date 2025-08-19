<!-- BEGIN: MAIN -->
<style>
  a[data-fancybox="gallery"] {
    display: block;
    text-decoration: none;
  }
  a[data-fancybox="gallery"]:hover {
    cursor: zoom-in; /* Курсор "лупа с плюсом" */
  }
  a[data-fancybox="gallery"]:hover img {
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    filter: brightness(1.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease, filter 0.3s ease;
  }
  a[data-fancybox="gallery"] img {
    transition: transform 0.3s ease, box-shadow 0.3s ease, filter 0.3s ease;
  }
  #container-carousel .carousel-item {
    height: auto; /* Высота подстраивается автоматически */
    position: relative;
    overflow: hidden;
    width: 100%; /* Гарантируем, что ширина контейнера 100% */
  }
  #container-carousel .carousel-item img {
    width: 100%; /* Изображение занимает всю ширину контейнера */
    height: auto; /* Высота подстраивается пропорционально */
    object-fit: contain; /* Изображение не обрезается, сохраняет пропорции */
    object-position: center; /* Центрирование изображения */
    position: static; /* Убираем абсолютное позиционирование */
    transform: none; /* Убираем transform, так как он не нужен */
  }
  .fancybox-image {
    border-radius: 15px;
  }
</style>

<div id="container-carousel" class="carousel slide " data-bs-ride="true">
  <div class="carousel-inner rounded-5 shadow-bottom">
<!-- BEGIN: ATTACHER_ROW -->
    <div class="carousel-item <!-- IF {ATTACHER_ROW_NUM} == '1' --> active<!-- ENDIF -->">
      	<a data-fancybox="gallery" data-src="{ATTACHER_ROW_URL}" data-caption="{ATTACHER_ROW_TITLE}">
	  <img src="{ATTACHER_ROW_URL}" alt="{ATTACHER_ROW_TITLE} - {ATTACHER_ROW_FILENAME}" title="{ATTACHER_ROW_TITLE} - {ATTACHER_ROW_FILENAME}"  class="img-fluid rounded" style="aspect-ratio: 1/1; object-fit: cover;">
	</a>
    </div>
  <!-- END: ATTACHER_ROW -->

  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#container-carousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#container-carousel" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>


    <script>
      Fancybox.bind('[data-fancybox="gallery"]', {
        Toolbar: {
          display: {
            left: ["infobar"],
            middle: [
              "zoomIn",
              "zoomOut",
              "toggle1to1",
              "rotateCCW",
              "rotateCW",
              "flipX",
              "flipY",
            ],
            right: ["slideshow", "thumbs", "close"],
          },
        },
      });    
    </script>

<!-- END: MAIN -->
