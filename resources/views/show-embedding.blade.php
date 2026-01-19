@extends('ai-monitor::layout', ['title' => 'AI Monitor — Request'])

@section('content')
    <div class="space-y-6 my-6">

        <div class="flex justify-between items-center gap-4">
            <div>
                <h1 class="mt-3 text-xl font-semibold tracking-tight text-white/90">
                    {{ $request->provider }} · {{ $request->operation_name }}
                </h1>

                <p class="mt-1 text-sm text-white/60">
                    {{ $request->created_at }} · model: {{ $request->model }}
                </p>
            </div>

            <div class="flex items-center justify-center gap-2">
                <a href="{{ route('ai-monitor.dashboard') }}" class="inline-flex items-center justify-center rounded-xl bg-white/5 px-4 py-2 text-sm font-semibold text-white/80 ring-1 ring-white/10 backdrop-blur hover:bg-white/10 hover:ring-white/20">
                    Назад
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 lg:grid-cols-12">
            <x-ai-monitor::metric-block
                title="Общее количество токенов"
                :value="$request->total_tokens"
                hint="Вход + Выход"
                class="lg:col-span-4"
            />
            <x-ai-monitor::metric-block
                title="Промпт"
                :value="$request->input_tokens"
                hint="Вход"
                class="lg:col-span-4"
            />
            <x-ai-monitor::metric-block
                title="Ответ"
                :value="$request->output_tokens"
                hint="Выход"
                class="lg:col-span-4"
            />

{{--            <div class="rounded-2xl bg-black/30 p-4 ring-1 ring-white/10 backdrop-blur lg:col-span-3">--}}
{{--                <div class="text-xs font-semibold uppercase tracking-wide text-white/50">Latency</div>--}}
{{--                <div class="mt-2 text-3xl font-semibold text-white/90">382<span class="text-base text-white/60"> ms</span></div>--}}
{{--                <div class="mt-2 text-xs text-white/50">End-to-end</div>--}}
{{--            </div>--}}
        </div>

        {{-- Secondary stats --}}
         <div class="grid grid-cols-1 gap-4 lg:grid-cols-12">
            <div class="rounded-2xl bg-white/5 ring-1 ring-white/10 backdrop-blur lg:col-span-4">
                <div class="border-b border-white/10 px-4 py-3">
                    <div class="text-sm font-semibold text-white/80">Итог</div>
                </div>
                <div class="space-y-3 px-4 py-4 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-white/50">Провайдер</span>
                        <span class="text-white/80">{{ $request->provider }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-white/50">Операция</span>
                        <span class="text-white/80">{{ $request->operation }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-white/50">Модель</span>
                        <span class="text-white/80">{{ $request->model }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-white/50">Стоимость (USD)</span>
                        <span class="font-mono text-emerald-300">${{ $request->cost_usd }}</span>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl bg-white/5 ring-1 ring-white/10 backdrop-blur lg:col-span-8">
                <div class="border-b border-white/10 px-4 py-3">
                    <div class="text-sm font-semibold text-white/80">Мета запроса</div>
                </div>

                <div class="grid grid-cols-1 gap-3 px-4 py-4 sm:grid-cols-2">
                    <div class="rounded-xl bg-black/30 p-3 ring-1 ring-white/10">
                        <div class="text-xs text-white/50">URL</div>
                        <div class="mt-1 truncate text-sm text-white/80">https://api.openai.com/v1/chat/completions</div>
                    </div>
                    <div class="rounded-xl bg-black/30 p-3 ring-1 ring-white/10">
                        <div class="text-xs text-white/50">Метод</div>
                        <div class="mt-1 text-sm text-white/80">POST</div>
                    </div>
                    <div class="rounded-xl bg-black/30 p-3 ring-1 ring-white/10">
                        <div class="text-xs text-white/50">Окружение</div>
                        <div class="mt-1 text-sm text-white/80">local</div>
                    </div>
                    <div class="rounded-xl bg-black/30 p-3 ring-1 ring-white/10">
                        <div class="text-xs text-white/50">Хост</div>
                        <div class="mt-1 text-sm text-white/80">server-01</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 lg:grid-cols-12">
            <div class="rounded-2xl bg-white/5 ring-1 ring-white/10 backdrop-blur lg:col-span-6">
                <div class="flex items-center justify-between border-b border-white/10 px-4 py-3">
                    <div class="text-sm font-semibold text-white/80">Запрос (сырой)</div>
                    <button class="rounded-xl bg-white/5 px-3 py-1.5 text-xs font-semibold text-white/70 ring-1 ring-white/10 hover:bg-white/10">
                        Copy
                    </button>
                </div>
                <pre class="max-h-130 h-full overflow-auto p-4 text-xs leading-relaxed text-white/80"><code>{{ json_encode($request->request, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
            </div>

            <div class="rounded-2xl bg-white/5 ring-1 ring-white/10 backdrop-blur lg:col-span-6">
                <div class="flex items-center justify-between border-b border-white/10 px-4 py-3">
                    <div class="text-sm font-semibold text-white/80">Ответ (сырой)</div>
                    <button class="rounded-xl bg-white/5 px-3 py-1.5 text-xs font-semibold text-white/70 ring-1 ring-white/10 hover:bg-white/10">
                        Copy
                    </button>
                </div>
                <pre class="max-h-130 h-full overflow-auto p-4 text-xs leading-relaxed text-white/80"><code>{{ json_encode($request->response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
            </div>
        </div>

    </div>
@endsection
