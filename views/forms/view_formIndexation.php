<form class="mx-3" method="POST" id="form" action="<?php echo $data['form']['action']; ?>">
    <input class="form-control col-6" type="text" pattern="(^https?:\/\/)?[a-zа-я0-9~_\-\.]+\.[a-zа-я]{2,9}(\/|:|\?[!-~]*)?$" placeholder="Введите домен сайта" name="url">
    <button class="btn btn-outline-secondary my-3" type="submit"><?php echo $data['form']['button']; ?></button>
</form>