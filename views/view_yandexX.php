<script src="../resource/js/ajax.js"></script>
<h2>Массовая Яндекс ИКС</h2>
<p >Инструмент позволяет узнать ИКС сайта. Этот показатель определяет, насколько сайт востребован у пользователей с точки зрения Яндекса. Можно использовать для своего или чужого сайта.</p>

<form method="POST" id="form" action="../tools/yandexx/action">
    <input type="text" pattern="(^https?:\/\/)?[a-zа-я0-9~_\-\.]+\.[a-zа-я]{2,9}(\/|:|\?[!-~]*)?$" placeholder="Введите домен: site.ru" name="site">
    <button type="submit">Проверить</button>
</form>

<div id="result"></div>