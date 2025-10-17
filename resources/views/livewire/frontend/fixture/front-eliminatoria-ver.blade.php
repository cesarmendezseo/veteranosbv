<div>
    <div id="bracket" class="overflow-x-auto"></div>

    <!-- En tu layout Blade -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jquery-bracket@0.11.1/dist/jquery.bracket.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-bracket@0.11.1/dist/jquery.bracket.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
    const data = {
      teams: @json($equipos),
      results: [@json($resultados)]
    };

    $('#bracket').bracket({
      init: data,
      skipConsolationRound: true,
      teamWidth: 120,
      matchMargin: 20,
      roundMargin: 50
    });
  });
    </script>
</div>