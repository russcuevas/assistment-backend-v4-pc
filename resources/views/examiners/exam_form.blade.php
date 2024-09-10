<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Examination Form</title>
</head>
<body>
    <h1>Welcome {{ $user->default_id }}</h1>
    <h1>Examination Form - Step {{ $currentStep }}</h1>

    <form action="{{ route('examiners.examination.submit') }}" method="POST">
        @csrf
        <input type="hidden" name="step" value="{{ $currentStep }}">

        @foreach($questions as $questionId => $questionGroup)
            <fieldset>
                <legend>{{ $questionGroup->first()->question_text }}</legend>

                @foreach($questionGroup as $item)
                    <div>
                        <label>
                            <input type="radio" name="question_{{ $questionId }}" value="{{ $item->option_id }}">
                            {{ $item->option_text }}
                        </label>
                    </div>
                @endforeach
            </fieldset>
            <br>
        @endforeach

        <button type="submit">
            {{ $currentStep < count($subjects) ? 'Next' : 'Submit Examination' }}
        </button>
    </form>
</body>
</html>
