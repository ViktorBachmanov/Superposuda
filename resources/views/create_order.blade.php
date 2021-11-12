<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        

        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }

            form {
	            display: flex;
	            flex-direction: column;
	            align-items: center;
	            justify-content: space-evenly;
	            height: 20rem;
	            width: 20rem;
	            box-shadow: 0px 0px 3px 2px gray;
            }

            label {
	            text-align: center;
            }

            input[type='text'] {
                width: 18rem;
                display: block;
            }

            textarea {
                width: 18rem;
            }

            button {
                cursor: pointer;
                padding: 0.5rem;
            }
        </style>

       
    </head>

    <body>
    <div>
        <form method="POST" action="/">
            @csrf

            <label>ФИО
                <input type='text' name='fio' required value='{{ $request->fio ?? "Бачманов Виктор Владимирович" }}'>
            </label>

            <label>Комментарий
                <textarea name='comment' required>{{ $request->comment ?? "https://github.com/ViktorBachmanov/Superposuda" }}</textarea>
            </label>

            <label>Артикул
                <input type='text' name='article' required value='{{ $request->article ?? "1202123" }}'>
            </label>

            <label>Бренд
                <input type='text' name='brand' required value='{{ $request->brand ?? "Staub" }}'>
            </label>

            <button type='submit' name='submit'>Отправить</button>
        </form>

        @if(isset($error) && $error)
            <h2>{{ $error }}</h2>
        @elseif(isset($order))
            <h3>Заказ создан успешно</h3>
            Статус заказа: {{ $order['order']['status'] }} <br>
            Тип заказа: {{ $order['order']['orderType'] }} <br>
            Магазин: {{ $order['order']['site'] }} <br>
            Способ оформления: {{ $order['order']['orderMethod'] }} <br>
            Номер заказа: {{ $order['order']['number'] }} <br>
            ФИО: {{ $order['order']['lastName'] }} {{ $order['order']['firstName'] }} {{ $order['order']['patronymic'] }} <br>
            Комментарий клиента: {{ $order['order']['customerComment'] }} <br>
            
        @endif

                
    </div>
    </body>
</html>
