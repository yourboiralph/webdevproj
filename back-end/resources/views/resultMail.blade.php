<!DOCTYPE html>
<html>
<head>
    <title>{{ $data['title'] }}</title>
</head>
<body>
    <h2>{{ $data['title'] }}</h2>

    <p>{{ $data['body'] }}</p>

    <h3>Result Data:</h3>

    @if ($data['result'])
        <p>ID: {{ $data['result']['id'] }}</p>
        <p>Taker: {{ $data['result']['taker'] }}</p>
        <p>Taken At: {{ $data['result']['taken_at'] }}</p>
        <p>Total Score: {{ $data['result']['total_score'] }}</p>
        <p>Depression Type: {{ $data['result']['depression_type']['type'] }}</p>

        <h4>Responses:</h4>
        <ul>
            @foreach ($data['result']['responses'] as $response)
                <li>
                    <p>Question: {{ $response['question'] }}</p>
                    <p>Answer: {{ $response['answer'] }}</p>
                    <p>Score: {{ $response['score'] }}</p>
                </li>
            @endforeach
        </ul>
    @else
        <p>No result data available.</p>
    @endif
</body>
</html>
