<script>
    var Agreed = confirm("You have questions? Then leave an appeal");
    if(Agreed)
    {
        var url = "{{ route('appeal') }}";
        window.location.href = url;
    }
</script>