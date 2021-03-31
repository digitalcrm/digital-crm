$(":checkbox").change(function() {
    $(this).closest("tr").find("td").toggleClass("checkedHighlight", this.checked);
});
// $("").change(function() {
//     $('').closest("table").find("tr").find("td").toggleClass("checkedHighlight", this.checked);
// });
function table_row_color_change() {
       $('.checkAll').closest("table").find("tr").find("td").toggleClass("checkedHighlight", this.checked);
    }