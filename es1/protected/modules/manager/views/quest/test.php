
<style>
    
    .many-select input[type=text] {
        width: 100%;
    }
    
    .many-select .list-select-items {
        display: none;
    }
</style>

<div class="many-select">
    <input type="text" readonly="1" value="Практика ; Числа, знаки и хрень ; Теория вероятностей" />
    <div class="list-select-items">
        <!-- всплывающее окно со список элементов -->
    </div>
</div>

<script type="text/javascript">
    
    var x = document.getElementsByClassName('many-select')[0].getElementsByTagName('input')[0];
    x.onclick = function() {
        
    };
      
</script>