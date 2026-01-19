@php use DarksLight2\AiRequestsMonitoring\Enums\Completions\YandexStatus;use DarksLight2\AiRequestsMonitoring\Enums\Completions\OpenAiStatus; @endphp
@extends('ai-monitor::layout', ['title' => 'AI Monitor — Dashboard'])

@section('content')
    <div class="space-y-6 mt-6">
        <div class="rounded-2xl bg-white/5 ring-1 ring-white/10 shadow-2xl backdrop-blur-xl">
            <div class="p-5">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-base font-semibold tracking-tight text-white/90">Дашборд</h3>
                        <p class="mt-1 text-sm text-white/60">Последние запросы и аналитика</p>
                    </div>
                </div>

                <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-3">
                    <div class="rounded-xl bg-black/20 ring-1 ring-white/10 p-4">
                        <div class="text-xs text-white/60">Токены (24h)</div>
                        <div class="mt-1 text-2xl font-semibold text-white/90">(under construct)</div>
                    </div>

                    <div class="rounded-xl bg-black/20 ring-1 ring-white/10 p-4">
                        <div class="text-xs text-white/60">Задержка</div>
                        <div class="mt-1 text-2xl font-semibold text-white/90">(under construct)</div>
                    </div>

                    <div class="rounded-xl bg-black/20 ring-1 ring-white/10 p-4">
                        <div class="text-xs text-white/60">Ошибки</div>
                        <div class="mt-1 text-2xl font-semibold text-white/90">(under construct)</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-2xl bg-white/5 ring-1 ring-white/10 shadow-2xl backdrop-blur-xl">
            <div class="p-5">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-base font-semibold tracking-tight text-white/90">Статистика по провайдеру (24ч)</h3>
                    </div>
                </div>

                <div class="mt-6 rounded-xl px-4 py-3">
                    <div class="grid grid-cols-12 gap-4 text-xs font-semibold uppercase tracking-wide text-white/50">
                        <div class="col-span-2">Провайдер</div>
                        <div class="col-span-2">Операция</div>
                        <div class="col-span-2">Модель</div>
                        <div class="col-span-1 text-right">Вход</div>
                        <div class="col-span-1 text-right">Выход</div>
                        <div class="col-span-2 text-right">Стоимость</div>
                    </div>
                </div>

                @foreach($groupedRequests as $request)
                    <div
                        class="mt-2 rounded-xl bg-black/30 ring-1 ring-white/10 backdrop-blur transition">
                        <div class="grid grid-cols-12 gap-4 px-4 py-4 items-center text-sm text-white/90">

                            <div class="col-span-2">
                            <span
                                class="inline-flex items-center rounded-full bg-white/10 px-3 py-1 text-xs font-semibold ring-1 ring-white/10">
                                {{ $request->provider }}
                            </span>
                            </div>

                            <div class="col-span-2 font-medium">
                                {{ $request->operation_name }}
                            </div>

                            <div class="col-span-2 text-white/70 truncate">
                                {{ $request->model }}
                            </div>

                            <div class="col-span-1 text-right font-mono">
                                {{ $request->input_tokens }}
                            </div>

                            <div class="col-span-1 text-right font-mono">
                                {{ $request->output_tokens }}
                            </div>

                            <div class="col-span-2 text-right font-mono text-emerald-300">
                                ${{ $request->cost_usd }}
                            </div>
                        </div>

                    </div>
                @endforeach

            </div>
        </div>

        <div class="rounded-2xl bg-white/5 ring-1 ring-white/10 shadow-2xl backdrop-blur-xl">
            <div class="p-5">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-base font-semibold tracking-tight text-white/90">Последние 10 запросов</h3>
                    </div>
                </div>

                <div class="mt-6 rounded-xl px-4 py-3">
                    <div class="grid grid-cols-12 gap-4 text-xs font-semibold uppercase tracking-wide text-white/50">
                        <div class="col-span-2">Провайдер</div>
                        <div class="col-span-2">Операция</div>
                        <div class="col-span-2">Модель</div>
                        <div class="col-span-1 text-right">Вход</div>
                        <div class="col-span-1 text-right">Выход</div>
                        <div class="col-span-2 text-right">Стоимость</div>
                        <div class="col-span-2 text-right">Статус</div>
                    </div>
                </div>

                @foreach($requests as $request)
                    <a href="{{ route('ai-monitor.show', ['id' => $request->id]) }}">
                        <div
                            class="mt-2 cursor-pointer rounded-xl bg-black/30 ring-1 ring-white/10 backdrop-blur transition hover:bg-white/10 hover:ring-white/20">
                            <div class="grid grid-cols-12 gap-4 px-4 py-4 items-center text-sm text-white/90">

                                <div class="col-span-2">
                                <span
                                    class="inline-flex items-center rounded-full bg-white/10 px-3 py-1 text-xs font-semibold ring-1 ring-white/10">
                                    {{ $request->provider }}
                                </span>
                                </div>

                                <div class="col-span-2 font-medium">
                                    {{ $request->operation_name }}
                                </div>

                                <div class="col-span-2 text-white/70 truncate">
                                    {{ $request->model }}
                                </div>

                                <div class="col-span-1 text-right font-mono">
                                    {{ $request->input_tokens }}
                                </div>

                                <div class="col-span-1 text-right font-mono">
                                    {{ $request->output_tokens }}
                                </div>

                                <div class="col-span-2 text-right font-mono text-emerald-300">
                                    ${{ $request->cost_usd }}
                                </div>

                                <div class="col-span-2 text-right">
                                <span
                                    @class([
                                        'inline-flex items-center gap-1 rounded-full px-2 py-1 text-xs font-semibold ring-1 ',
                                        'bg-emerald-400/10 text-emerald-300 ring-emerald-400/20' => $request->status === YandexStatus::Stop || $request->status === YandexStatus::UsedTool || $request->status === OpenAiStatus::UsedTool || $request->status === OpenAiStatus::Stop,
                                        'bg-gray-400/10 text-gray-300 ring-gray-400/20' => is_null($request->status),
                                        'bg-purple-400/10 text-purple-300 ring-purple-400/20' => $request->status === YandexStatus::ContentModerated || $request->status === OpenAiStatus::ContentModerated,
                                        'bg-red-400/10 text-red-300 ring-red-400/20' => $request->status === YandexStatus::Error || $request->status === YandexStatus::MaxTokenLimit || $request->status === OpenAiStatus::MaxTokenLimit || $request->status === YandexStatus::ConnectionError || $request->status === OpenAiStatus::ConnectionError,
                                    ])>
                                    {{ $request->status?->toString() ?? 'N/A' }}
                                </span>
                                </div>
                            </div>

                        </div>
                    </a>
                @endforeach

            </div>
        </div>
    </div>
@endsection
