<form id="mainForm" method="POST" action="/analytics">
    <h1 class="mb-3">Анализ сайта</h1>    
    <div class="col-12 mb-2">
	    <input class="form-control mb-3" type="text" pattern="(^https?:\/\/)?[a-zа-я0-9~_\-\.]+\.[a-zа-я]{2,9}(\/|:|\?[!-~]*)?$" placeholder="Введите сайт: site.ru" name="site">
	    <button class="btn btn-outline-secondary" type="submit" onclick="displayLoading('spinner')" id="button">Анализировать</button>
    </div>
    <div id="spinner" style="display: none;">
	    <div class="spinner-border " role="status">
	  		<span class="sr-only">Loading...</span>
		</div>
		<p>Подождите, идет загрузка...</p>
	</div>
</form>