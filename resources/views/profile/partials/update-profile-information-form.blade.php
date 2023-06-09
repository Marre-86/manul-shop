            <section>
                <div class="card-header">
                  <h3>{{ __('Profile Information') }}</h3>
                </div>

                <div class="card-body">

                  <p>{{ __("Update your account's profile information and email address.") }}</p>
                  <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                    @csrf
                  </form>

                  <form method="post" action="{{ route('profile.update') }}">
                    @csrf
                    @method('patch')

                    <div class="form-group" style="margin-bottom:10px">
                      <x-input-label for="name" :value="__('Name')" />
                      <x-text-input id="name" name="name" type="text" class="form-control" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                      <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <div class="form-group" style="margin-bottom:10px">
                      <x-input-label for="email" :value="__('Email')" />
                      <x-text-input id="email" name="email" type="email" class="form-control" :value="old('email', $user->email)" required autocomplete="username" />
                      <x-input-error class="mt-2" :messages="$errors->get('email')" />

                      @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div>
                          <p class="text-sm mt-2 text-gray-800">
                            {{ __('Your email address is unverified.') }}

                            <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                              {{ __('Click here to re-send the verification email.') }}
                            </button>
                          </p>

                          @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-green-600">
                              {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                          @endif
                        </div>
                      @endif
                    </div>

                    <div class="form-group" style="margin-bottom:10px">
                      <x-input-label for="phone" :value="__('Phone')" />
                      <x-text-input id="phone" name="phone" type="text" class="form-control" :value="old('phone', $user->phone)" autofocus autocomplete="phone" />
                      <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                    </div>

                    <div class="form-group">
                      <div style="display:inline-block;">
                        <x-primary-button class="btn btn-success" style="display:inline-block;">{{ __('Save') }}</x-primary-button>
                      </div>
                      @if (session('status') === 'profile-updated')
                        <div style="display:inline-block;">
                          <p
                            x-data="{ show: true }"
                            x-show="show"
                            x-transition
                            x-init="setTimeout(() => show = false, 2000)"
                            class="text-muted"                          
                          >{{ __('Saved.') }}</p>
                        </div>
                      @endif
                    </div>
                  </form>
                </div>
            </section>
