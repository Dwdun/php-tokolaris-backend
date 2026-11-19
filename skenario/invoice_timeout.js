import http from 'k6/http';
import { check } from 'k6';

const BASE_URL = __ENV.BASE_URL || 'http://web:80';

// Simulasi 20 pengguna serentak mencetak PDF invoice (T15)
export const options = {
  vus: 20,
  duration: '5s',
  thresholds: {
    http_req_duration: ['p(95)<500'], // Target setelah async worker: < 500ms
  },
};

export default function () {
  const res = http.get(`${BASE_URL}/api/v1/invoices/1001`);
  check(res, {
    'status is 200': (r) => r.status === 200,
  });
}
