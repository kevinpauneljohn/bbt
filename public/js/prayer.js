let prayer_id;
$(document).on('click','.mark-answered-btn',function(){
    prayer_id = this.id;

    Swal.fire({
        title: 'Answered Prayer?',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Complete it!'
    }).then((result) => {
        if (result.value === true) {
            $.ajax({
                url: '/prayer-requests/answered/'+prayer_id,
                type: 'POST',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function(response){
                    console.log(response);
                    if(response.success === true)
                    {
                        let table = $('#prayer-requests').DataTable();
                        table.ajax.reload(null, false);
                        Swal.fire(
                            'Completed!',
                            response.message,
                            'success'
                        )
                    }
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });

        }
    });
});
