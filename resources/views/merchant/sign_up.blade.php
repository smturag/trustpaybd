@extends('welcome')
@section('customer')

@php
$app_name = app_config('AppName');
$image = app_config('AppLogo');
@endphp

    <div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 pt-24">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <a href="{{ route('home') }}" class="flex justify-center text-3xl font-bold text-indigo-600 mb-8">{{ $app_name }}</a>
            <h2 class="text-center text-3xl font-bold text-gray-900">Create your merchant account</h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Already have an account?
                <a href="{{ route('merchantlogin') }}" class="font-medium text-indigo-600 hover:text-indigo-500">Sign in</a>
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow-lg sm:rounded-lg sm:px-10 border border-gray-100">
                <form class="space-y-6" action="{{ route('merchant.sign_up.submit') }}" method="POST">
                    @csrf
                    @if ($errors->any())
                        <div class="mb-4">
                            <div class="text-red-600 font-semibold">Whoops! Something went wrong:</div>
                            <ul class="mt-2 text-sm text-red-500">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if(session('success'))
                        <div class="mb-4 text-green-600 font-semibold">{{ session('success') }}</div>
                    @endif

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Full name</label>
                        <div class="mt-1">
                            <input id="name" name="name" type="text" required
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Enter your full name">
                        </div>
                    </div>

                    <div>
                        <label for="company" class="block text-sm font-medium text-gray-700">Company / Shop name</label>
                        <div class="mt-1">
                            <input id="company" name="company" type="text" required
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Enter your business name">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">This will be displayed on your payment receipts</p>
                    </div>

                    <div>
                        <label for="website" class="block text-sm font-medium text-gray-700">Business Website</label>
                        <div class="mt-1">
                            <div class="flex rounded-md shadow-sm">
                                <span
                                    class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    https://
                                </span>
                                <input type="text" name="website" id="website"
                                    class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="www.yourwebsite.com">
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Optional - Enter your business website URL</p>
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                        <div class="mt-1">
                            <input id="email" name="email" type="email" required
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="you@example.com">
                        </div>
                    </div>

                    <div>
                        <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                        <div class="mt-1">
                            <select id="country" name="country" required
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select your country</option>
                                <option value="af">Afghanistan (+93)</option>
                                <option value="al">Albania (+355)</option>
                                <option value="dz">Algeria (+213)</option>
                                <option value="as">American Samoa (+1)</option>
                                <option value="ad">Andorra (+376)</option>
                                <option value="ao">Angola (+244)</option>
                                <option value="ai">Anguilla (+1)</option>
                                <option value="ag">Antigua and Barbuda (+1)</option>
                                <option value="ar">Argentina (+54)</option>
                                <option value="am">Armenia (+374)</option>
                                <option value="aw">Aruba (+297)</option>
                                <option value="au">Australia (+61)</option>
                                <option value="at">Austria (+43)</option>
                                <option value="az">Azerbaijan (+994)</option>
                                <option value="bs">Bahamas (+1)</option>
                                <option value="bh">Bahrain (+973)</option>
                                <option value="bd">Bangladesh (+880)</option>
                                <option value="bb">Barbados (+1)</option>
                                <option value="by">Belarus (+375)</option>
                                <option value="be">Belgium (+32)</option>
                                <option value="bz">Belize (+501)</option>
                                <option value="bj">Benin (+229)</option>
                                <option value="bm">Bermuda (+1)</option>
                                <option value="bt">Bhutan (+975)</option>
                                <option value="bo">Bolivia (+591)</option>
                                <option value="ba">Bosnia and Herzegovina (+387)</option>
                                <option value="bw">Botswana (+267)</option>
                                <option value="br">Brazil (+55)</option>
                                <option value="io">British Indian Ocean Territory (+246)</option>
                                <option value="vg">British Virgin Islands (+1)</option>
                                <option value="bn">Brunei (+673)</option>
                                <option value="bg">Bulgaria (+359)</option>
                                <option value="bf">Burkina Faso (+226)</option>
                                <option value="bi">Burundi (+257)</option>
                                <option value="kh">Cambodia (+855)</option>
                                <option value="cm">Cameroon (+237)</option>
                                <option value="ca">Canada (+1)</option>
                                <option value="cv">Cape Verde (+238)</option>
                                <option value="bq">Caribbean Netherlands (+599)</option>
                                <option value="ky">Cayman Islands (+1)</option>
                                <option value="cf">Central African Republic (+236)</option>
                                <option value="td">Chad (+235)</option>
                                <option value="cl">Chile (+56)</option>
                                <option value="cn">China (+86)</option>
                                <option value="cx">Christmas Island (+61)</option>
                                <option value="cc">Cocos Islands (+61)</option>
                                <option value="co">Colombia (+57)</option>
                                <option value="km">Comoros (+269)</option>
                                <option value="cd">Congo - Kinshasa (+243)</option>
                                <option value="cg">Congo - Brazzaville (+242)</option>
                                <option value="ck">Cook Islands (+682)</option>
                                <option value="cr">Costa Rica (+506)</option>
                                <option value="ci">Côte d’Ivoire (+225)</option>
                                <option value="hr">Croatia (+385)</option>
                                <option value="cu">Cuba (+53)</option>
                                <option value="cw">Curaçao (+599)</option>
                                <option value="cy">Cyprus (+357)</option>
                                <option value="cz">Czechia (+420)</option>
                                <option value="dk">Denmark (+45)</option>
                                <option value="dj">Djibouti (+253)</option>
                                <option value="dm">Dominica (+1)</option>
                                <option value="do">Dominican Republic (+1)</option>
                                <option value="ec">Ecuador (+593)</option>
                                <option value="eg">Egypt (+20)</option>
                                <option value="sv">El Salvador (+503)</option>
                                <option value="gq">Equatorial Guinea (+240)</option>
                                <option value="er">Eritrea (+291)</option>
                                <option value="ee">Estonia (+372)</option>
                                <option value="sz">Eswatini (+268)</option>
                                <option value="et">Ethiopia (+251)</option>
                                <option value="fk">Falkland Islands (+500)</option>
                                <option value="fo">Faroe Islands (+298)</option>
                                <option value="fj">Fiji (+679)</option>
                                <option value="fi">Finland (+358)</option>
                                <option value="fr">France (+33)</option>
                                <option value="gf">French Guiana (+594)</option>
                                <option value="pf">French Polynesia (+689)</option>
                                <option value="ga">Gabon (+241)</option>
                                <option value="gm">Gambia (+220)</option>
                                <option value="ge">Georgia (+995)</option>
                                <option value="de">Germany (+49)</option>
                                <option value="gh">Ghana (+233)</option>
                                <option value="gi">Gibraltar (+350)</option>
                                <option value="gr">Greece (+30)</option>
                                <option value="gl">Greenland (+299)</option>
                                <option value="gd">Grenada (+1)</option>
                                <option value="gp">Guadeloupe (+590)</option>
                                <option value="gu">Guam (+1)</option>
                                <option value="gt">Guatemala (+502)</option>
                                <option value="gg">Guernsey (+44)</option>
                                <option value="gn">Guinea (+224)</option>
                                <option value="gw">Guinea-Bissau (+245)</option>
                                <option value="gy">Guyana (+592)</option>
                                <option value="ht">Haiti (+509)</option>
                                <option value="hn">Honduras (+504)</option>
                                <option value="hk">Hong Kong (+852)</option>
                                <option value="hu">Hungary (+36)</option>
                                <option value="is">Iceland (+354)</option>
                                <option value="in">India (+91)</option>
                                <option value="id">Indonesia (+62)</option>
                                <option value="ir">Iran (+98)</option>
                                <option value="iq">Iraq (+964)</option>
                                <option value="ie">Ireland (+353)</option>
                                <option value="im">Isle of Man (+44)</option>
                                <option value="il">Israel (+972)</option>
                                <option value="it">Italy (+39)</option>
                                <option value="jm">Jamaica (+1)</option>
                                <option value="jp">Japan (+81)</option>
                                <option value="je">Jersey (+44)</option>
                                <option value="jo">Jordan (+962)</option>
                                <option value="kz">Kazakhstan (+7)</option>
                                <option value="ke">Kenya (+254)</option>
                                <option value="ki">Kiribati (+686)</option>
                                <option value="xk">Kosovo (+383)</option>
                                <option value="kw">Kuwait (+965)</option>
                                <option value="kg">Kyrgyzstan (+996)</option>
                                <option value="la">Laos (+856)</option>
                                <option value="lv">Latvia (+371)</option>
                                <option value="lb">Lebanon (+961)</option>
                                <option value="ls">Lesotho (+266)</option>
                                <option value="lr">Liberia (+231)</option>
                                <option value="ly">Libya (+218)</option>
                                <option value="li">Liechtenstein (+423)</option>
                                <option value="lt">Lithuania (+370)</option>
                                <option value="lu">Luxembourg (+352)</option>
                                <option value="mo">Macau (+853)</option>
                                <option value="mg">Madagascar (+261)</option>
                                <option value="mw">Malawi (+265)</option>
                                <option value="my">Malaysia (+60)</option>
                                <option value="mv">Maldives (+960)</option>
                                <option value="ml">Mali (+223)</option>
                                <option value="mt">Malta (+356)</option>
                                <option value="mh">Marshall Islands (+692)</option>
                                <option value="mq">Martinique (+596)</option>
                                <option value="mr">Mauritania (+222)</option>
                                <option value="mu">Mauritius (+230)</option>
                                <option value="yt">Mayotte (+262)</option>
                                <option value="mx">Mexico (+52)</option>
                                <option value="fm">Micronesia (+691)</option>
                                <option value="md">Moldova (+373)</option>
                                <option value="mc">Monaco (+377)</option>
                                <option value="mn">Mongolia (+976)</option>
                                <option value="me">Montenegro (+382)</option>
                                <option value="ms">Montserrat (+1)</option>
                                <option value="ma">Morocco (+212)</option>
                                <option value="mz">Mozambique (+258)</option>
                                <option value="mm">Myanmar (+95)</option>
                                <option value="na">Namibia (+264)</option>
                                <option value="nr">Nauru (+674)</option>
                                <option value="np">Nepal (+977)</option>
                                <option value="nl">Netherlands (+31)</option>
                                <option value="nc">New Caledonia (+687)</option>
                                <option value="nz">New Zealand (+64)</option>
                                <option value="ni">Nicaragua (+505)</option>
                                <option value="ne">Niger (+227)</option>
                                <option value="ng">Nigeria (+234)</option>
                                <option value="nu">Niue (+683)</option>
                                <option value="nf">Norfolk Island (+672)</option>
                                <option value="kp">North Korea (+850)</option>
                                <option value="mk">North Macedonia (+389)</option>
                                <option value="mp">Northern Mariana Islands (+1)</option>
                                <option value="no">Norway (+47)</option>
                                <option value="om">Oman (+968)</option>
                                <option value="pk">Pakistan (+92)</option>
                                <option value="pw">Palau (+680)</option>
                                <option value="ps">Palestine (+970)</option>
                                <option value="pa">Panama (+507)</option>
                                <option value="pg">Papua New Guinea (+675)</option>
                                <option value="py">Paraguay (+595)</option>
                                <option value="pe">Peru (+51)</option>
                                <option value="ph">Philippines (+63)</option>
                                <option value="pl">Poland (+48)</option>
                                <option value="pt">Portugal (+351)</option>
                                <option value="pr">Puerto Rico (+1)</option>
                                <option value="qa">Qatar (+974)</option>
                                <option value="re">Réunion (+262)</option>
                                <option value="ro">Romania (+40)</option>
                                <option value="ru">Russia (+7)</option>
                                <option value="rw">Rwanda (+250)</option>
                                <option value="bl">Saint Barthélemy (+590)</option>
                                <option value="sh">Saint Helena (+290)</option>
                                <option value="kn">Saint Kitts and Nevis (+1)</option>
                                <option value="lc">Saint Lucia (+1)</option>
                                <option value="mf">Saint Martin (+590)</option>
                                <option value="pm">Saint Pierre and Miquelon (+508)</option>
                                <option value="vc">Saint Vincent and the Grenadines (+1)</option>
                                <option value="ws">Samoa (+685)</option>
                                <option value="sm">San Marino (+378)</option>
                                <option value="st">São Tomé and Príncipe (+239)</option>
                                <option value="sa">Saudi Arabia (+966)</option>
                                <option value="sn">Senegal (+221)</option>
                                <option value="rs">Serbia (+381)</option>
                                <option value="sc">Seychelles (+248)</option>
                                <option value="sl">Sierra Leone (+232)</option>
                                <option value="sg">Singapore (+65)</option>
                                <option value="sx">Sint Maarten (+1)</option>
                                <option value="sk">Slovakia (+421)</option>
                                <option value="si">Slovenia (+386)</option>
                                <option value="sb">Solomon Islands (+677)</option>
                                <option value="so">Somalia (+252)</option>
                                <option value="za">South Africa (+27)</option>
                                <option value="kr">South Korea (+82)</option>
                                <option value="ss">South Sudan (+211)</option>
                                <option value="es">Spain (+34)</option>
                                <option value="lk">Sri Lanka (+94)</option>
                                <option value="sd">Sudan (+249)</option>
                                <option value="sr">Suriname (+597)</option>
                                <option value="se">Sweden (+46)</option>
                                <option value="ch">Switzerland (+41)</option>
                                <option value="sy">Syria (+963)</option>
                                <option value="tw">Taiwan (+886)</option>
                                <option value="tj">Tajikistan (+992)</option>
                                <option value="tz">Tanzania (+255)</option>
                                <option value="th">Thailand (+66)</option>
                                <option value="tl">Timor-Leste (+670)</option>
                                <option value="tg">Togo (+228)</option>
                                <option value="tk">Tokelau (+690)</option>
                                <option value="to">Tonga (+676)</option>
                                <option value="tt">Trinidad and Tobago (+1)</option>
                                <option value="tn">Tunisia (+216)</option>
                                <option value="tr">Turkey (+90)</option>
                                <option value="tm">Turkmenistan (+993)</option>
                                <option value="tc">Turks and Caicos Islands (+1)</option>
                                <option value="tv">Tuvalu (+688)</option>
                                <option value="ug">Uganda (+256)</option>
                                <option value="ua">Ukraine (+380)</option>
                                <option value="ae">United Arab Emirates (+971)</option>
                                <option value="gb">United Kingdom (+44)</option>
                                <option value="us">United States (+1)</option>
                                <option value="uy">Uruguay (+598)</option>
                                <option value="uz">Uzbekistan (+998)</option>
                                <option value="vu">Vanuatu (+678)</option>
                                <option value="va">Vatican City (+39)</option>
                                <option value="ve">Venezuela (+58)</option>
                                <option value="vn">Vietnam (+84)</option>
                                <option value="vi">Virgin Islands (+1)</option>
                                <option value="wf">Wallis and Futuna (+681)</option>
                                <option value="eh">Western Sahara (+212)</option>
                                <option value="ye">Yemen (+967)</option>
                                <option value="zm">Zambia (+260)</option>
                                <option value="zw">Zimbabwe (+263)</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Mobile number</label>
                        <div class="mt-1">
                            <input type="tel" id="phone" name="phone" required
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Enter mobile number">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">We'll send a verification code to this number</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Password</label>
                        <div class="mt-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <input id="password" name="password" type="password" required
                                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Create a strong password">
                            </div>
                            <div>
                                <input id="confirm-password" name="password_confirmation" type="password" required
                                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Confirm your password">
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Password must be at least 8 characters long</p>
                    </div>

                    <div class="flex items-center">
                        <input id="terms" name="terms" type="checkbox" required
                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="terms" class="ml-2 block text-sm text-gray-900">
                            I agree to the <a href="#" class="text-indigo-600 hover:text-indigo-500">Terms</a> and
                            <a href="#" class="text-indigo-600 hover:text-indigo-500">Privacy Policy</a>
                        </label>
                    </div>

                    <div>
                        <button type="submit"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Create account
                        </button>
                    </div>
                </form>

                <div class="mt-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">Or continue with</span>
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-2 gap-3">
                        <div>
                            <a href="#"
                                class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <i class="fab fa-google text-xl"></i>
                            </a>
                        </div>
                        <div>
                            <a href="#"
                                class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <i class="fab fa-facebook text-xl"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.1.1/css/intlTelInput.min.css" />
    @endpush
    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.1.1/js/intlTelInput.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var input = document.querySelector("#phone");
                window.iti = window.intlTelInput(input, {
                    initialCountry: "auto",
                    separateDialCode: true,
                    nationalMode: false,
                    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.1.1/js/utils.js"
                });

                // Sync country select with intl-tel-input
                var countrySelect = document.getElementById('country');
                countrySelect.addEventListener('change', function() {
                    window.iti.setCountry(this.value);
                });
                input.addEventListener('countrychange', function() {
                    countrySelect.value = window.iti.getSelectedCountryData().iso2;
                });

                // Optional: format value before submit
                input.form.closest('form').addEventListener('submit', function(e) {
                    input.value = iti.getNumber();
                });
            });
        </script>
    @endpush
@endsection
