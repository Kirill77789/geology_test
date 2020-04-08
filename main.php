<main class="baseWidth">
    <form class="protoWidth" method="post">
        <div class="mainFormLegend">Тестирование Управления геологии, испытания и КРС</div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" value="testable" checked>
            <label class="form-check-label" for="exampleRadios1">
                Пройти тест
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios2" value="admin">
            <label class="form-check-label" for="exampleRadios2">
                Администратор
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios3" value="developer">
            <label class="form-check-label" for="exampleRadios3">
                Разработчик
            </label>
        </div>
        <div class="form-group">
            <label for="formGroupExampleInput">Фамилия Имя Отчество:</label>
            <input type="text" class="form-control" id="formGroupExampleInput" name="FIO"  placeholder="Зубенко Михаил Петрович">
        </div>
        <div class="form-group">
            <label for="formGroupExampleInput_2">Должность:</label>
            <input type="text" class="form-control" id="formGroupExampleInput_2" name="position" placeholder="Главный геолог">
        </div>
        <div class="form-group">
            <label for="formGroupExampleInput_3">Структурное подразделение:</label>
            <input type="text" class="form-control" id="formGroupExampleInput_3" name="subdivision" placeholder="Служба испытания и КРС">
        </div>
        <div class="form-group">
            <label for="exampleFormControlSelect1">Филиал</label>
            <select class="form-control" id="exampleFormControlSelect1" name="branch" >
                <option>ЦАУ</option>
                <option>ООО "Уренгой бурение"</option>
                <option>ООО "Краснодар бурение"</option>
                <option>ООО "Оренбург бурение"</option>
                <option>ООО "Астрахань бурение</option>
                <option>ПАО "Подзембургаз"</option>
            </select>
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input type="password" class="form-control" id="exampleInputPassword1" name="password" placeholder="Password">
        </div>
        <button type="submit" class="btn btn-primary startButton" name="start_test" value="testWasStart">Начать тестирование</button>
    </form>
</main>