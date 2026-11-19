import http from 'k6/http';
import { check } from 'k6';

const BASE_URL = __ENV.BASE_URL || 'http://web:80';

export const options = {
  scenarios: {
    flash_sale_rush: {
      executor: 'per-vu-iterations',
      vus: 50,              // 50 Virtual Users bersamaan
      iterations: 1,        // Masing-masing mencoba checkout 1 kali
      maxDuration: '10s',
    },
  },
  thresholds: {
    http_req_failed: ['rate<0.10'],
    http_req_duration: ['p(95)<500'],
  },
};

export default function () {
  const payload = JSON.stringify({
    user_id: 1,
    product_id: 101, // Item Flash Sale: Stok awal = 10 unit!
    quantity: 1,
    payment_method: 'bank_transfer',
  });

  const params = {
    headers: {
      'Content-Type': 'application/json',
      'X-User-Id': `user_${__VU}`,
    },
  };

  const res = http.post(`${BASE_URL}/api/v1/orders/checkout`, payload, params);

  check(res, {
    'status is 200 or 400': (r) => r.status === 200 || r.status === 400,
  });
}
