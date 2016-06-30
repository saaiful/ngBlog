$('.dd').nestable({});
$(".dd").on('change', function() {
  // console.log($('.dd').nestable('serialize'));
  var data = window.JSON.stringify($('.dd').nestable('serialize'));
  data = {
    json: data
  };
  $.ajax({
    type: "POST",
    url: 'menu.php',
    data: data,
    success: function() {
      $("#modal-menu").modal('hide');
      loadMenu();
    }
  });
});

function newItem() {
  $("#modal-menu").modal('show');
}

function newItemSave() {
  data = {
    'new': "save",
    "name": $("#name").val(),
    "link": $("#link").val()
  };
  $.ajax({
    type: "POST",
    url: 'menu.php',
    data: data,
    success: function() {
      $("#modal-menu").modal('hide');
      loadMenu();
    }
  });
}

function loadMenu() {
  $.getJSON("menu.json", function(data) {
    $('.dd-list').html("");
    document.html = '';
    $.each(data, function(index, value) {
      var len = Object.keys(value).length;
      if (len == 3) {
        document.html += '<li class="dd-item dd3-item" data-id="' + value.id + '" data-name="' + value.name + '" data-link="' + value.link + '"><div class="dd-handle dd3-handle">Drag</div><div class="dd3-content">' + value.name + '</div></li>';
      }
      if (len > 3) {
        document.html += '<li class="dd-item" data-id="' + value.id + '" data-name="' + value.name + '" data-link="' + value.link + '">';
        document.html += '<div class="dd3-content">' + value.name + '</div>';
        document.html += '<div class="dd-handle dd3-handle">Drag</div>';
        document.html += '<ol class="dd-list">';
        $.each(value.children, function(a, b) {
          document.html += '<li class="dd-item" data-id="' + b.id + '" data-name="' + b.name + '" data-link="' + b.link + '"><div class="dd-handle dd3-handle">Drag</div><div class="dd3-content">' + b.name + '</div></li>';
        });
        document.html += '</ol>';
        document.html += '</li>';
      }
    });
    $('.dd-list').append(document.html);
  });
}
loadMenu();

function deletePost(id) {
  swal({
    title: "Are you sure?",
    text: "You will not be able to recover this post!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#DD6B55",
    confirmButtonText: "Yes, delete it!",
    closeOnConfirm: false
  }, function() {
    $.get("delete.php?id=" + id, function(data) {
      swal("Deleted!", "Your imaginary file has been deleted.", "success");
      window.location = 'index.php';
    });
  });
}