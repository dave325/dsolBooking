function changeCategory(obj){
    var $this = jQuery(obj),
        $input = $this.parents(".tb_taxonomy").find(".widefat"),
        arr = $input.val().split(",");
    if ($this.is(":checked")) {
        arr.push($this.val());
        var emptyKey = arr.indexOf("");
        if (emptyKey > -1) {
            arr.splice(emptyKey, 1);
        }
    } else {
        var foundKey = arr.indexOf($this.val());
        if (foundKey > -1) {
            arr.splice(foundKey, 1);
        }
    }
    $input.val(arr.join(","));
}

(function($) {
    "use strict";
    $(document).ready(function($) {
        
    });
})(jQuery);