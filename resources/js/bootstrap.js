import axios from 'axios';
window.axios = axios;
import * as Popper from '@popperjs/core'
window.Popper = Popper
import 'bootstrap'

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
