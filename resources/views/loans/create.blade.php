<x-app-layout>
    <div class="max-w-3xl mx-auto">
        <h1 class="text-3xl font-bold text-white mb-8 drop-shadow-md">Apply for a Loan</h1>

        <!-- Glass Form Container -->
        <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-lg border border-white/20 p-8 w-full">
            <form method="POST" action="{{ route('loans.store') }}" class="space-y-6">
                @csrf

                <!-- Loan Type -->
                <div>
                    <x-input-label for="loan_type_id" :value="__('Loan Type')" class="text-stark-dark font-semibold" />
                    <select id="loan_type_id" name="loan_type_id" class="block mt-2 w-full border-gray-300 focus:border-stark-blue focus:ring-stark-blue rounded-xl shadow-sm bg-white/80" required>
                        <option value="">Select a loan type</option>
                        @foreach($loanTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }} ({{ $type->interest_rate }}% Interest)</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('loan_type_id')" class="mt-2" />
                </div>

                <!-- Amount -->
                <div>
                    <x-input-label for="amount" :value="__('Loan Amount')" class="text-stark-dark font-semibold" />
                    <x-text-input id="amount" class="block mt-2 w-full bg-white/80" type="number" name="amount" min="100" step="0.01" required autofocus placeholder="e.g., 5000" />
                    <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                </div>

                <!-- Purpose -->
                <div>
                    <x-input-label for="purpose" :value="__('Purpose (Optional)')" class="text-stark-dark font-semibold" />
                    <textarea id="purpose" name="purpose" rows="3" class="block mt-2 w-full border-gray-300 focus:border-stark-blue focus:ring-stark-blue rounded-xl shadow-sm bg-white/80" placeholder="e.g., Home renovation, Business expansion..."></textarea>
                    <x-input-error :messages="$errors->get('purpose')" class="mt-2" />
                </div>

                <!-- Due Date -->
                <div>
                    <x-input-label for="due_date" :value="__('Expected Repayment Date (Optional)')" class="text-stark-dark font-semibold" />
                    <x-text-input id="due_date" class="block mt-2 w-full bg-white/80" type="date" name="due_date" />
                    <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
                </div>

                <!-- Submit Button -->
                <div class="pt-4 flex justify-end">
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-stark-blue border border-transparent rounded-xl font-semibold text-white uppercase tracking-wide hover:bg-stark-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-stark-blue transition-all shadow-lg hover:shadow-xl">
                        Submit Application
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>