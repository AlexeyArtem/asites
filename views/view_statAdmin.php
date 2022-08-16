<div class="mx-3 mt-4">    
    <h2>Статистика запросов</h1>
    <p>Статистика запросов анализа сайтов за все время.</p>
    <table class="statTable table table-dark table-striped table-hover table-sm  w-50 mx-auto">
        <thead class="thead-default">
            <tr>
                <th>Дата</th>
                <th>Сайт</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $count = count($data);
                if($count == 0) "<tr><td>0</td><td>0</td><tr>";
                else
                {
                    for($i = 0; $i < count($data); $i++)
                    {
                    $date = $data[$i]['Date'];
                    $site = $data[$i]['Site'];

                    echo "<tr><td>$date</td><td>$site</td><tr>";        
                    }
                }
            ?>
        </tbody>
    </table>
</div>