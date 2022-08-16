<!-- <?php 
    print_r($data);
?> -->

<div class="mx-4 mt-2 p-2 rounded-lg bg-white">
    <h2>Яндекс ИКС</h2>
    <p>ИКС: 
        <?php
            $value = $data['yandexX']['value'];
            $link = $data['yandexX']['link'];
            echo "<a target='_blank' href='$link'>$value</a>";
        ?>
    </p>
</div>

<div class="mx-4 mt-2 p-2  rounded-lg bg-white ">
    <h2>Возраст сайта</h2>
    <p>Возраст: <?php echo $data['ageDomain']; ?></p>
</div>

<div class="mx-4 mt-2 p-2 rounded-lg bg-white">
    <h2>Счетчик Google Analytics</h2>
    <p>Номер счетчика: 
    	<?php
    		if(empty($data['counter']['counterGA'])) $result = "cчетчик не найден";
    		else $result = $data['counter']['counterGA'];
    		echo $result;
    	?>
    </p>
</div>

<div class="mx-4 mt-2 p-2 rounded-lg bg-white">
    <h2>Счетчик Yandex Metrika</h2>
    <p>Номер счетчика:
    	<?php
    		if(empty($data['counter']['counterYM'])) $result = "cчетчик не найден";
    		else $result = $data['counter']['counterYM'];
    		echo $result;
    	?>
    	
    </p>
</div>

<div class="mx-4 mt-2 p-2 rounded-lg bg-white">
    <h2>Наличие файла robots.txt</h2>
    <p>
        <?php
            if($data['robots'] == 1) $result = "Файл robots.txt найден"; 
            else $result = "файл robots.txt не найден";
            echo $result;
        ?>
    </p>
</div>

<div class="mx-4 mt-2 p-2 rounded-lg bg-white">
    <h2>Наличие sitemap</h2>
    <p>
        <?php
            if(empty($data['sitemap'])) $result = "Карта сайта не найдена"; 
            else
            {
                $url = '"' . $data['sitemap'] . '"';
                $result = "Как минимум одна " . "<a target='_blank' href=$url>карта сайта</a>" . " найдена и доступна.";
            }
            echo $result;
        ?>
    </p>
</div>

<div class="mx-4 mt-2 p-2 rounded-lg bg-white">
    <h2>Скорость загрузки HTML</h2>
    <p>
        <?php 
            $result = round($data['server']['curlInfo']['total_time'], 3);
            echo $result . " сек.";
        ?>
    </p>
</div>

<div class="mx-4 mt-2 p-2 rounded-lg bg-white">
    <h2>Размер страницы</h2>
    <p>
        <?php 
            $result = round($data['server']['curlInfo']['size_download'] / 1024, 3);
            echo $result . " КБ";
        ?>
    </p>
</div>

<div class="mx-4 mt-2 p-2 rounded-lg bg-white">
    <h2>Заголовок страницы</h2>
    <p>
        <?php 
            $result = $data['tags']['titleValue'];
            if(empty($result)) $result = "Не найдено.";
            echo $result;
        ?>
    </p>
</div>

<div class="mx-4 mt-2 p-2 rounded-lg bg-white">
    <h2>Описание</h2>
    <p>
        <?php
            $result = $data['tags']['description'];
            if(empty($result)) $result = "Не найдено.";
            echo $result;
        ?>
    </p>
</div>

<div class="mx-4 mt-2 p-2 rounded-lg bg-white">
    <h2>Количество заголовков H1-H6</h2>
    <ul>
        <?php
            for($i = 1; $i <= 6; $i++)
            {
                $count = 0;
                $key = "h" . $i;
                
                if(array_key_exists($key, $data['tags']['tagsHvalue']))
                {
                    $array = $data['tags']['tagsHvalue'][$key];
                    if(!empty($array)) $count = count($array);
                }
                echo "<li>H" . $i . ": " . $count . "</li>";
            }
        ?>
        <!-- <li>H1:</li>
        <li>H2:</li>
        <li>H3:</li>
        <li>H4:</li>
        <li>H5:</li>
        <li>H6:</li> -->
    </ul>
</div>

<div class="mx-4 mt-2 p-2  rounded-lg bg-white">
    <h2>Скорость загрузки от Google</h2>
    <div class="row mx-4 mb-4">
        <div class="col-6">
            <h3>Скорость с компьютера</h3>
        </div>
        <div class="col-6">
            <ol class="list-counter-circle">
                <li class="">Время загрузки достаточной части контента: <?php echo $data['deskSpeed']['first-contentful-paint']; ?></li>
                <li class="">Индекс скорости: <?php echo $data['deskSpeed']['speed-index']; ?></li>
                <li class="">Отрисовка крупного контента: <?php echo $data['deskSpeed']['largest-contentful-paint']; ?></li>
                <li class="">Время до момента возможности взаимодейтсвия: <?php echo $data['deskSpeed']['time-to-interactive']; ?></li>
                <li class="">Время задержки при вводе: <?php echo $data['deskSpeed']['total-blocking-time']; ?></li>
                <li class="">Смещение макета: <?php echo $data['deskSpeed']['cumulative-layout-shift']; ?></li>
            </ol>  
        </div>
    </div>
    <div class="row mx-4">
        <div class="col-6">
            <h3>Скорость с мобильного</h3>
        </div>
        <div class="col-6">
            <ol class="list-counter-circle">
                <li class="">Время загрузки достаточной части контента: <?php echo $data['mobSpeed']['first-contentful-paint']; ?></li>
                <li class="">Индекс скорости: <?php echo $data['mobSpeed']['speed-index']; ?></li>
                <li class="">Отрисовка крупного контента: <?php echo $data['mobSpeed']['largest-contentful-paint']; ?></li>
                <li class="">Время до момента возможности взаимодейтсвия: <?php echo $data['mobSpeed']['time-to-interactive']; ?></li>
                <li class="">Время задержки при вводе: <?php echo $data['mobSpeed']['total-blocking-time']; ?></li>
                <li class="">Смещение макета: <?php echo $data['mobSpeed']['cumulative-layout-shift']; ?></li>
            </ol> 
        </div>
    </div>
</div>