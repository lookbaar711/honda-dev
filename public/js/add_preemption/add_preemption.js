
var expose_preemptions = {
  add_Preemption: function() {
    // console.log('111111111111');
        var wrapper_tel         = $(".action_add_Preemption"); //Fields wrapper
        var add_button_tel      = $(".input_add_Preemption"); //Add button ID
        var x = 1; //initlal text box count
        x++;
        var str = `<div class="form-group row">
                    <label for="staticEmail" class="col-4 col-form-label text-right"> </label>
                    <div class="col-6">
                      <input type="text" class="form-control">
                    </div>
                    <button type="button" class="buttonplus btn btn-danger remove_fieldtel" title="View">
                      <i class="fa fa-trash-o"></i>
                    </button>
                  </div>`;


          $(wrapper_tel).append(str); //add input box

          $(wrapper_tel).on("click",".remove_fieldtel", function(e){ //user click on remove text
            $(this).parent('div').remove(); x--;
          })


      },
}
