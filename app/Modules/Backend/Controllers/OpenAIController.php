<?php

namespace App\Modules\Backend\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use OpenAI\OpenAI;
use Illuminate\Support\Facades\Response;
// use Orhanerday\OpenAi\OpenAi;

class OpenAIController extends Controller
{
    public function view(Request $request)
    {
        return $this->getView($this->getFolderView('openai.openai'),[
            'title' => __('Add new car'),
            'new' => true
        ]);
    }

    public function write(Request $request)
    {
        $title   = $request->title ?? '';
        $open_ai_key = getenv('OPENAI_API_KEY');
        // $open_ai = new OpenAi($open_ai_key);

        // $chat = $open_ai->chat([
        // 'model' => 'gpt-3.5-turbo',
        // 'messages' => [
        //     [
        //         "role" => "system",
        //         "content" => "You are a helpful assistant."
        //     ],
        //     [
        //         "role" => "user",
        //         "content" => "Who won the world series in 2020?"
        //     ],
        //     // [
        //     //     "role" => "assistant",
        //     //     "content" => "The Los Angeles Dodgers won the World Series in 2020."
        //     // ],
        //     // [
        //     //     "role" => "user",
        //     //     "content" => "Where was it played?"
        //     // ],
        // ],
        // 'temperature' => 1.0,
        // 'max_tokens' => 4000,
        // 'frequency_penalty' => 0,
        // 'presence_penalty' => 0,
        // ]);


        // // var_dump($chat);
        // // echo "<br>";
        // // echo "<br>";
        // // echo "<br>";
        // // // decode response
        // $d = json_decode($chat);
        // // // Get Content
        // // echo($d->choices[0]->message->content);
        // $content = $d->choices[0]->message->content;

        // // return view('openai.openai', compact('title', 'content'));
        // return $this->getView($this->getFolderView('openai.openai'),[
        //     'title' => __('Add new car'),
        //     'new' => true,
        //     'content' => $content
        // ]);

        $client = OpenAI::client($open_ai_key);

        // --If necessary, it is possible to configure and create a separate client.
        // $client = OpenAI::factory()
        //     ->withApiKey($open_ai_key)
        //     ->withOrganization('org-11XJXwi7h4UWtwBPgLYYLj62') // default: null
        //     ->withBaseUri('openai.example.com/v1') // default: api.openai.com/v1
        //     ->withHttpClient($client = new \GuzzleHttp\Client([])) // default: HTTP client found using PSR-18 HTTP Client Discovery
        //     ->withHttpHeader('X-My-Header', 'foo')
        //     ->withQueryParam('my-param', 'bar')
        //     ->withStreamHandler(fn (RequestInterface $request): ResponseInterface => $client->send($request, [
        //         'stream' => true // Allows to provide a custom stream handler for the http client.
        //     ]))
        //     ->make();

        // --Lists the currently available models
        // $response = $client->models()->list();
        // $response->object; // 'list'
        // foreach ($response->data as $result) {
        //     $result->id; // 'gpt-3.5-turbo-instruct'
        //     $result->object; // 'model'
        //     // ...
        // }
        // $response->toArray(); // ['object' => 'list', 'data' => [...]]
        // dd($response);


        // --Retrieves a model instance
        // $response = $client->models()->retrieve('gpt-3.5-turbo-instruct');
        // $response->id; // 'gpt-3.5-turbo-instruct'
        // $response->object; // 'model'
        // $response->created; // 1642018370
        // $response->ownedBy; // 'openai'
        // $response->toArray(); // ['id' => 'gpt-3.5-turbo-instruct', ...]


        // --Delete a fine-tuned model.
        // $response = $client->models()->delete('curie:ft-acmeco-2021-03-03-21-44-20');
        // $response->id; // 'curie:ft-acmeco-2021-03-03-21-44-20'
        // $response->object; // 'model'
        // $response->deleted; // true
        // $response->toArray(); // ['id' => 'curie:ft-acmeco-2021-03-03-21-44-20', ...]


        // --Creates a completion for the provided prompt and parameters.
        // $response = $client->completions()->create([
        //     'model' => 'gpt-3.5-turbo-instruct',
        //     'prompt' => 'Say this is a test',
        //     'max_tokens' => 6,
        //     'temperature' => 0
        // ]);       
        // $response->id; // 'cmpl-uqkvlQyYK7bGYrRHQ0eXlWi7'
        // $response->object; // 'text_completion'
        // $response->created; // 1589478378
        // $response->model; // 'gpt-3.5-turbo-instruct'       
        // foreach ($response->choices as $result) {
        //     $result->text; // '\n\nThis is a test'
        //     $result->index; // 0
        //     $result->logprobs; // null
        //     $result->finishReason; // 'length' or null
        // }       
        // $response->usage->promptTokens; // 5,
        // $response->usage->completionTokens; // 6,
        // $response->usage->totalTokens; // 11      
        // $response->toArray(); // ['id' => 'cmpl-uqkvlQyYK7bGYrRHQ0eXlWi7', ...]


        // --Creates a streamed completion for the provided prompt and parameters.
        // $stream = $client->completions()->createStreamed([
        //     'model' => 'gpt-3.5-turbo-instruct',
        //     'prompt' => 'Hi',
        //     'max_tokens' => 10,
        // ]);    
        // foreach($stream as $response){
        //     $response->choices[0]->text;
        // }


        //--Creates a completion for the chat message.
        // $response = $client->chat()->create([
        //     'model' => 'gpt-3.5-turbo',
        //     'messages' => [
        //         ['role' => 'user', 'content' => 'Hello!'],
        //     ],
        // ]);      
        // $response->id; // 'chatcmpl-6pMyfj1HF4QXnfvjtfzvufZSQq6Eq'
        // $response->object; // 'chat.completion'
        // $response->created; // 1677701073
        // $response->model; // 'gpt-3.5-turbo-0301'       
        // foreach ($response->choices as $result) {
        //     $result->index; // 0
        //     $result->message->role; // 'assistant'
        //     $result->message->content; // '\n\nHello there! How can I assist you today?'
        //     $result->finishReason; // 'stop'
        // }       
        // $response->usage->promptTokens; // 9,
        // $response->usage->completionTokens; // 12,
        // $response->usage->totalTokens; // 21       
        // $response->toArray(); // ['id' => 'chatcmpl-6pMyfj1HF4QXnfvjtfzvufZSQq6Eq', ...]


        // ---Creates a completion for the chat message with a tool call.
        // $response = $client->chat()->create([
        //     'model' => 'gpt-3.5-turbo-0613',
        //     'messages' => [
        //         ['role' => 'user', 'content' => 'What\'s the weather like in Boston?'],
        //     ],
        //     'tools' => [
        //         [
        //             'type' => 'function',
        //             'function' => [
        //                 'name' => 'get_current_weather',
        //                 'description' => 'Get the current weather in a given location',
        //                 'parameters' => [
        //                     'type' => 'object',
        //                     'properties' => [
        //                         'location' => [
        //                             'type' => 'string',
        //                             'description' => 'The city and state, e.g. San Francisco, CA',
        //                         ],
        //                         'unit' => [
        //                             'type' => 'string',
        //                             'enum' => ['celsius', 'fahrenheit']
        //                         ],
        //                     ],
        //                     'required' => ['location'],
        //                 ],
        //             ],
        //         ]
        //     ]
        // ]);   
        // $response->id; // 'chatcmpl-6pMyfj1HF4QXnfvjtfzvufZSQq6Eq'
        // $response->object; // 'chat.completion'
        // $response->created; // 1677701073
        // $response->model; // 'gpt-3.5-turbo-0613'       
        // foreach ($response->choices as $result) {
        //     $result->index; // 0
        //     $result->message->role; // 'assistant'
        //     $result->message->content; // null
        //     $result->message->toolCalls[0]->id; // 'call_123'
        //     $result->message->toolCalls[0]->type; // 'function'
        //     $result->message->toolCalls[0]->function->name; // 'get_current_weather'
        //     $result->message->toolCalls[0]->function->arguments; // "{\n  \"location\": \"Boston, MA\"\n}"
        //     $result->finishReason; // 'tool_calls'
        // }       
        // $response->usage->promptTokens; // 82,
        // $response->usage->completionTokens; // 18,
        // $response->usage->totalTokens; // 100



        // ---Creates a completion for the chat message with a function call
        // $response = $client->chat()->create([
        //     'model' => 'gpt-3.5-turbo-0613',
        //     'messages' => [
        //         ['role' => 'user', 'content' => 'What\'s the weather like in Boston?'],
        //     ],
        //     'functions' => [
        //         [
        //             'name' => 'get_current_weather',
        //             'description' => 'Get the current weather in a given location',
        //             'parameters' => [
        //                 'type' => 'object',
        //                 'properties' => [
        //                     'location' => [
        //                         'type' => 'string',
        //                         'description' => 'The city and state, e.g. San Francisco, CA',
        //                     ],
        //                     'unit' => [
        //                         'type' => 'string',
        //                         'enum' => ['celsius', 'fahrenheit']
        //                     ],
        //                 ],
        //                 'required' => ['location'],
        //             ],
        //         ]
        //     ]
        // ]);
        
        // $response->id; // 'chatcmpl-6pMyfj1HF4QXnfvjtfzvufZSQq6Eq'
        // $response->object; // 'chat.completion'
        // $response->created; // 1677701073
        // $response->model; // 'gpt-3.5-turbo-0613'
        
        // foreach ($response->choices as $result) {
        //     $result->index; // 0
        //     $result->message->role; // 'assistant'
        //     $result->message->content; // null
        //     $result->message->functionCall->name; // 'get_current_weather'
        //     $result->message->functionCall->arguments; // "{\n  \"location\": \"Boston, MA\"\n}"
        //     $result->finishReason; // 'function_call'
        // }
        
        // $response->usage->promptTokens; // 82,
        // $response->usage->completionTokens; // 18,
        // $response->usage->totalTokens; // 100


        // -----Creates a streamed completion for the chat message.
        // $stream = $client->chat()->createStreamed([
        //     'model' => 'gpt-3.5-turbo',
        //     'messages' => [
        //         ['role' => 'user', 'content' => 'Hello!'],
        //     ],
        // ]);
        
        // foreach($stream as $response){
        //     $response->choices[0]->toArray();
        // }



        // -----Generates audio from the input text.
        $audio = $client->audio()->speech([
            'model' => 'tts-1',
            'input' => '안녕 친구들, 좋은 하루 보내요, 정말 사랑해요',
            'voice' => 'alloy',
        ]);
        $audioDirectory = storage_path('app/public/audio/');       
        if (!file_exists($audioDirectory)) {
            mkdir($audioDirectory, 0777, true);
        }
        $audioFileName = 'german.mp3';       
        $audioFilePath = $audioDirectory . $audioFileName;
        file_put_contents($audioFilePath, $audio);
        $publicPath = asset('storage/audio/' . $audioFileName);
        return response()->json(['audio_url' => $publicPath]);

        
        // ----Generates streamed audio from the input text.
        // $stream = $client->audio()->speechStreamed([
        //     'model' => 'tts-1',
        //     'input' => 'The quick brown fox jumped over the lazy dog.',
        //     'voice' => 'alloy',
        // ]);
        
        // foreach($stream as $chunk){
        //     $chunk; // chunk of audio file content as string
        //     echo $chunk;
        //     ob_flush();
        //     flush();
        // }


        // ------transcribe Transcribes audio into the input language.
        // $response = $client->audio()->transcribe([
        //     'model' => 'whisper-1',
        //     'file' => fopen('storage/audio/german.mp3', 'r'),
        //     'response_format' => 'verbose_json',
        //     'timestamp_granularities' => ['segment', 'word']
        // ]);
        // $response->task; // 'transcribe'
        // $response->language; // 'english'
        // $response->duration; // 2.95
        // $response->text; // 'Hello, how are you?'
        
        // foreach ($response->segments as $segment) {
        //     $segment->id; // 0
        //     $segment->seek; // 0
        //     $segment->start; // 0.0
        //     $segment->end; // 4.0
        //     $segment->text; // 'Hello, how are you?'
        //     $segment->tokens; // [50364, 2425, 11, 577, 366, 291, 30, 50564]
        //     $segment->temperature; // 0.0
        //     $segment->avgLogprob; // -0.45045216878255206
        //     $segment->compressionRatio; // 0.7037037037037037
        //     $segment->noSpeechProb; // 0.1076972484588623
        //     $segment->transient; // false
        // }
        
        // foreach ($response->words as $word) {
        //     $word->word; // 'Hello'
        //     $word->start; // 0.31
        //     $word->end; // 0.92
        // }
        
        // $response->toArray();
        // dd($response);

        // --Translates audio into English.
        // $response = $client->audio()->translate([
        //     'model' => 'whisper-1',
        //     'file' => fopen('storage/audio/german.mp3', 'r'),
        //     'response_format' => 'verbose_json',
        // ]);      
        // $response->task; // 'translate'
        // $response->language; // 'english'
        // $response->duration; // 2.95
        // $response->text; // 'Hello, how are you?'       
        // foreach ($response->segments as $segment) {
        //     $segment->id; // 0
        //     $segment->seek; // 0
        //     $segment->start; // 0.0
        //     $segment->end; // 4.0
        //     $segment->text; // 'Hello, how are you?'
        //     $segment->tokens; // [50364, 2425, 11, 577, 366, 291, 30, 50564]
        //     $segment->temperature; // 0.0
        //     $segment->avgLogprob; // -0.45045216878255206
        //     $segment->compressionRatio; // 0.7037037037037037
        //     $segment->noSpeechProb; // 0.1076972484588623
        //     $segment->transient; // false
        // }       
        // $response->toArray(); // ['task' => 'translate', ...]



        // ---Creates an embedding vector representing the input text.
        // $response = $client->embeddings()->create([
        //     'model' => 'text-embedding-ada-002',
        //     'input' => 'The food was delicious and the waiter...',
        // ]);       
        // $response->object; // 'list'
        
        // foreach ($response->embeddings as $embedding) {
        //     $embedding->object; // 'embedding'
        //     $embedding->embedding; // [0.018990106880664825, -0.0073809814639389515, ...]
        //     $embedding->index; // 0
        // }     
        // $response->usage->promptTokens; // 8,
        // $response->usage->totalTokens; // 8      
        // $response->toArray(); 


        //----- Returns a list of files that belong to the user's organization.
        // $response = $client->files()->list();
        // $response->object; // 'list'
        // foreach ($response->data as $result) {
        //     $result->id; // 'file-XjGxS3KTG0uNmNOK362iJua3'
        //     $result->object; // 'file'
        //     // ...
        // }
        // $response->toArray(); // ['object' => 'list', 'data' => [...]]
        // dd($response);


        // ----Delete a file.
        // $response = $client->files()->delete('file-vaoR3dZOEZqGUOnWKIBtg7Cf');
        // $response->id; // 'file-XjGxS3KTG0uNmNOK362iJua3'
        // $response->object; // 'file'
        // $response->deleted; // true
        // $response->toArray(); // ['id' => 'file-XjGxS3KTG0uNmNOK362iJua3', ...]
        // dd($response);


        // ----Returns information about a specific file.
        // $response = $client->files()->retrieve('file-6R9PORjzGbfakY6MMYEDV6cd');
        // $response->id; // 'file-XjGxS3KTG0uNmNOK362iJua3'
        // $response->object; // 'file'
        // $response->bytes; // 140
        // $response->createdAt; // 1613779657
        // $response->filename; // 'mydata.jsonl'
        // $response->purpose; // 'fine-tune'
        // $response->status; // 'succeeded'
        // $response->statusDetails; // null
        // $response->toArray(); // ['id' => 'file-XjGxS3KTG0uNmNOK362iJua3', ...]
        // dd($response);


        // ----Upload a file that contains document(s) to be used across various endpoints/features.
        // $response = $client->files()->upload([
        //     'purpose' => 'fine-tune',
        //     'file' => fopen('storage/train/trainFile.jsonl', 'r'),
        // ]);
    
        // $response->id; // 'file-XjGxS3KTG0uNmNOK362iJua3'
        // $response->object; // 'file'
        // $response->bytes; // 140
        // $response->createdAt; // 1613779657
        // $response->filename; // 'mydata.jsonl'
        // $response->purpose; // 'fine-tune'
        // $response->status; // 'succeeded'
        // $response->statusDetails; // null
        
        // $response->toArray(); // ['id' => 'file-XjGxS3KTG0uNmNOK362iJua3', ...]
        // dd($response);


        // ------Returns the contents of the specified file.
        // $response = $client->files()->download('file-GtBx6s8gQ3pOCwJw7EWVzmab');
        // dd($response);


        // $response = $client->files()->create([
        //     "file"=>open("storage/train/trainFile.jsonl", "rb"),
        //     "purpose"=>"fine-tune"
        // ]);
        // dd($response);

        // ----Creates a job that fine-tunes a specified model from a given dataset.
        // $response = $client->fineTuning()->createJob([
        //     'training_file' => 'file-abc123',
        //     'validation_file' => null,
        //     'model' => 'gpt-3.5-turbo',
        //     'hyperparameters' => [
        //         'n_epochs' => 4,
        //     ],
        //     'suffix' => null,
        // ]);
        
        // $response->id; // 'ftjob-AF1WoRqd3aJAHsqc9NY7iL8F'
        // $response->object; // 'fine_tuning.job'
        // $response->model; // 'gpt-3.5-turbo-0613'
        // $response->fineTunedModel; // null
        // // ...
        
        // $response->toArray(); // ['id' => 'ftjob-AF1WoRqd3aJAHsqc9NY7iL8F', ...]
        // dd($response); // ['id' => 'ftjob-AF1WoR



        // ---List your organization's fine-tuning jobs.
        // $response = $client->fineTuning()->listJobs();
        // You can pass additional parameters to the listJobs method to narrow down the results.
        // $response = $client->fineTuning()->listJobs([
        //     'limit' => 10, // Number of jobs to retrieve (Default: 20)
        //     // 'after' => 'ftjob-AF1WoRqd3aJAHsqc9NY7iL8F', // Identifier for the last job from the previous pagination request.
        // ]);
        // $response->object; // 'list'
        // foreach ($response->data as $result) {
        //     $result->id; // 'ftjob-AF1WoRqd3aJAHsqc9NY7iL8F'
        //     $result->object; // 'fine_tuning.job'
        //     // ...
        // }
        // $response->toArray(); // ['object' => 'list', 'data' => [...]]
        // dd($response);


        //--- Get info about a fine-tuning job.
        // $response = $client->fineTuning()->retrieveJob('ftjob-VUgqcFjmpP7Z8CTkCl6ltGAO');
        // $response->id; // 'ftjob-AF1WoRqd3aJAHsqc9NY7iL8F'
        // $response->object; // 'fine_tuning.job'
        // $response->model; // 'gpt-3.5-turbo-0613'
        // $response->createdAt; // 1614807352
        // $response->finishedAt; // 1692819450
        // $response->fineTunedModel; // 'ft:gpt-3.5-turbo-0613:jwe-dev::7qnxQ0sQ'
        // $response->organizationId; // 'org-jwe45798ASN82s'
        // $response->resultFiles; // 'file-1bl05WrhsKDDEdg8XSP617QF'
        // $response->status; // 'succeeded'
        // $response->validationFile; // null
        // $response->trainingFile; // 'file-abc123'
        // $response->trainedTokens; // 5049

        // $response->hyperparameters->nEpochs; // 9

        // $response->toArray(); // ['id' => 'ftjob-AF1WoRqd3aJAHsqc9NY7iL8F', ...]
        // dd($response);


        // ----Immediately cancel a fine-tune job.
        // $response = $client->fineTuning()->cancelJob('ftjob-VUgqcFjmpP7Z8CTkCl6ltGAO');

        // $response->id; // 'ftjob-AF1WoRqd3aJAHsqc9NY7iL8F'
        // $response->object; // 'fine_tuning.job'
        // // ...
        // $response->status; // 'cancelled'
        // // ...

        // $response->toArray(); // ['id' => 'ftjob-AF1WoRqd3aJAHsqc9NY7iL8F', ...]
        // dd($response);



        $response = $client->fineTuning()->listJobEvents('ftjob-VUgqcFjmpP7Z8CTkCl6ltGAO');

        $response->object; // 'list'

        foreach ($response->data as $result) {
            $result->object; // 'fine_tuning.job.event' 
            $result->createdAt; // 1614807352
            // ...
        }

        $response->toArray(); // ['object' => 'list', 'data' => [...]]
        dd($response);


        // $result = $client->chat()->create([
        //     'model' => 'gpt-3.5-turbo',
        //     'messages' => [
        //         ['role' => 'user', 'content' => 'Hello!'],
        //     ],
        // ]);

        // echo $result->choices[0]->message->content;
    }
}