$(function() {
    // Sidebar toggle (collapses/expands sidebar)
    $('#sidebarToggle').on('click', function() {
      $('.sidebar').toggleClass('collapsed');
      $('.sidebar').toggleClass('show');
      if(window.innerWidth <= 991){
        $('.sidebar').toggleClass('show');
      }
    });

    // Select all
    $('#selectAll').click(function() {
        $('.selectbox').prop('checked', this.checked);
        toggleBulkBtns();
    });
    $('.selectbox').change(toggleBulkBtns);

    function toggleBulkBtns() {
        let any = $('.selectbox:checked').length > 0;
        $('#bulkDeleteBtn').prop('disabled', !any);
    }

    // Bulk delete
    $('#bulkDeleteBtn').click(function(e){
        if(confirm("Delete selected contacts?")) {
            $('#bulkActionForm').submit();
        }
    });

    // Inline editing
    $('.editable').on('blur', function(){
        let td = $(this);
        let value = td.text().trim();
        let field = td.data('field');
        let id = td.closest('tr').data('id');
        $.post('inline_edit.php', {id, field, value}, function(resp){
            if(resp !== 'OK') alert(resp);
        });
    });
    // Handle Enter key for inline editing
    $('.editable').on('keydown', function(e){
        if(e.key === 'Enter') {
            e.preventDefault();
            $(this).blur();
        }
    });

    // Responsive sidebar toggle on window resize
    $(window).on('resize', function() {
      if(window.innerWidth > 991){
        $('.sidebar').removeClass('show');
      }
    });
});