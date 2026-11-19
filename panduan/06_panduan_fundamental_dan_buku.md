# Matrix Kompetensi Backend Developer: Legacy System Mastery
> Dokumen referensi komprehensif untuk Full-Stack / Backend Engineer yang mengelola, memodernisasi, dan menavigasi sistem legacy berskala produksi.

---

## 🧭 Peta Konseptual: Apa itu "Knowledge Fundamental"?

Sebelum masuk ke detail kategori, penting untuk membedakan ketiga dimensi kompetensi:
1. **Hard Skill (Tooling & Teknik):** Kemampuan mengoperasikan alat, menulis sintaks, dan mengeksekusi prosedur teknis (contoh: menulis query SQL, menjalankan profiler, konfigurasi CI/CD).
2. **Soft Skill (Perilaku & Karakter):** Sikap mental, regulasi emosi, etika, dan cara berkomunikasi dengan manusia lain (contoh: kesabaran, negosiasi risiko, empati).
3. **Knowledge Fundamental (Prinsip & Teori):** Pemahaman mendalam tentang *bagaimana komputer, sistem operasi, jaringan, basis data, dan organisasi manusia bekerja secara mekanis*.
   - **Knowledge Diperlukan:** Prinsip dasar yang wajib dipahami agar tidak merusak sistem saat bertindak.
   - **Knowledge Dihasilkan:** Mental model mendalam dan intuisi sistemik yang baru terbentuk setelah berulang kali menyelesaikan masalah nyata di lapangan.

---

## 📊 Ringkasan Matrix Kategori (A — J)

| Kategori | Fokus Utama | Knowledge Fundamental Diperlukan | Knowledge Fundamental Dihasilkan |
| :--- | :--- | :--- | :--- |
| **A** | Bug Fix di Sistem Rapuh | Execution Model, Call Stack, Concurrency Primitives | System State Dynamics & Cascading Failure Mechanics |
| **B** | Memahami & Dokumentasi | Software Architecture Styles, Relational Algebra, Git DAG | Lehman’s Laws of Software Evolution & Socio-technical Archeology |
| **C** | Tambah Fitur Tanpa Rusak | SOLID/Coupling-Cohesion, Contract Invariants | Seam Theory, Mutation Containment & Blast Radius Calculus |
| **D** | Database & Migrasi Data | B-Tree Internals, ACID & Isolation Levels, Locking Engine | Zero-Downtime Expand-Contract Dynamics & Asynchronous Consistency |
| **E** | Testing Tanpa Test | Test Double Taxonomy, State Isolation, Feedback Loops | Characterization Preservation vs Correctness & Testability Feedback |
| **F** | Upgrade Framework/Dep | AST, SemVer Mechanics, Runtime Engine Evolution | Dependency Graph Topology & Compounding Technical Debt Theory |
| **G** | Modernisasi & Arsitektur | Distributed Systems Foundations (CAP), Integration Patterns | Evolutionary Architecture & Dual-Write Hazard Mitigation |
| **H** | Keamanan di Sistem Lama | CIA Triad, Cryptographic Primitives, Injection Vectors | Threat Surface Expansion & Inline Migration of Crypto-States |
| **I** | Incident & Production | OS Resource Limits (FD/OOM), Observability (USE/RED) | Normal Accidents Theory, Resilience Engineering & Triage Dynamics |
| **J** | Kolaborasi & Kepemimpinan | Conway’s Law, Software Economics (TCO), Cognitive Load | Technical Diplomacy, Paved Road Architecture & Historical Empathy |

---

## 🔍 Pembahasan Mendalam per Kategori

---

### Kategori A — Bug Fix di Sistem Rapuh

> **Konteks:** Menemukan dan memperbaiki kerusakan tersembunyi pada basis kode yang tidak memiliki test, minim dokumentasi, dan penuh efek samping (*side-effects*).

#### 1. Hard Skill
- Debugging sistematis: baca stack trace, reproduce bug, binary search penyebab
- Logging & log analysis (grep, structured logs, regex)
- SQL untuk investigasi data (cek inkonsistensi data aneh langsung di DB)
- Pemahaman konkurensi praktis (race condition, atomic operation, locking)
- Profiling dasar (memory leak, CPU spike — heap snapshot, APM)

#### 2. Soft Skill
- Kesabaran & ketelitian — bug legacy jarang ketemu dalam hitungan jam
- Berpikir hipotesis-dulu, bukan asal ubah kode (*"tebak → buktikan → baru fix"*)
- Menahan diri dari fix tambal sulam vs tahu kapan tambal sulam justru tepat secara pragmatis

#### 3. Knowledge Fundamental yang Diperlukan (Prerequisites)
- **Execution Model & Memory Stack vs Heap:** Cara kerja call stack, alokasi memori, garbage collection, dan lifecycle propagasi exception.
- **Concurrency & Threading Mechanics:** Konsep mutual exclusion, critical section, deadlocks, livelocks, dan thread pool exhaustion.
- **I/O & Network Semantics:** Karakteristik blocking vs non-blocking I/O, socket timeout, serta connection pool lifecycle.

#### 4. Knowledge Fundamental yang Dihasilkan / Didapat (Acquired)
- **System State Dynamics:** Pemahaman mendalam bahwa pada sistem besar, bug hampir tidak pernah berdiri sendiri; mereka adalah manifestasi dari *state mutation* yang tidak sinkron lintas layer.
- **Cascading Failure Mechanics:** Memahami bagaimana satu exception kecil yang tidak tertangani dapat menyedot thread pool dan melumpuhkan seluruh service (*domino effect*).
- **Empirical vs Theoretical Runtime Behavior:** Menyadari bahwa perilaku aktual sistem sering menyimpang dari dokumentasi resmi bahasa/framework akibat konfigurasi OS, latency jaringan, atau perilaku driver database historis.

---

### Kategori B — Memahami & Mendokumentasikan Sistem

> **Konteks:** Melakukan "arkeologi software" untuk memetakan bagaimana sistem bekerja, apa fungsi bisnisnya, dan mengapa keputusan masa lalu diambil.

#### 1. Hard Skill
- Membaca kode orang lain dengan cepat (*code navigation*, trace dari entry point)
- Git forensics: blame, log history, bisect sejarah keputusan
- Analisis access log / APM untuk tahu endpoint & modul yang masih aktif vs zombie code
- Membaca skema DB & reverse-engineering relasi fisik menjadi model domain
- Menulis dokumentasi teknis yang terstruktur (README, diagram C4/Mermaid, ADR)

#### 2. Soft Skill
- Rasa ingin tahu detektif — tahan membaca kode berjam-jam tanpa menyentuh keyboard untuk menulis kode baru
- Bertanya ke orang — ke tim support, ops, developer lama; banyak jawaban tidak ada di kode
- Mendengarkan aktif saat menggali konteks bisnis
- Merangkum hal rumit menjadi peta sederhana yang bisa dipahami orang lain

#### 3. Knowledge Fundamental yang Diperlukan (Prerequisites)
- **Software Architecture Styles:** Pemahaman pola Monolith, Layered/N-Tier, MVC, Domain-Driven Design (Bounded Context), dan Spaghetti/Big Ball of Mud.
- **Relational Data Modeling:** Entitas, relasi 1:N, N:M, foreign key constraints, normalisasi (1NF-3NF), dan denormalisasi yang disengaja.
- **Network & Protocol Basics:** HTTP status code, request lifecycle, headers, serialization format (JSON, XML, SOAP, form-encoded).
- **Git Directed Acyclic Graph (DAG):** Cara kerja object graph Git (blobs, trees, commits, tags) untuk menelusuri sejarah percabangan secara akurat.

#### 4. Knowledge Fundamental yang Dihasilkan / Didapat (Acquired)
- **Lehman’s Laws of Software Evolution:** Hukum perubahan perangkat lunak: sistem yang digunakan pasti akan terus berubah, dan kompleksitasnya akan terus naik kecuali ada usaha aktif untuk merawatnya.
- **Mental Mapping of Implicit Dependencies:** Kemampuan memetakan ketergantungan implisit (misal: Service A berkomunikasi dengan Service B lewat baris data tertentu di tabel DB bersama tanpa API).
- **Reverse-Engineering Heuristics:** Intuisi psikologis dalam memahami gaya coding developer masa lalu (misal: "Oh, fungsi ini ditulis terburu-buru saat deadline rilis 5 tahun lalu, jadi validasi inputnya dilewati").

---

### Kategori C — Menambah Fitur Tanpa Merusak

> **Konteks:** Mengintegrasikan logika bisnis baru ke dalam sistem yang sudah ada tanpa memicu regresi pada fitur lama yang saling terkait erat.

#### 1. Hard Skill
- Design pattern praktis: Adapter, Decorator, Strategy, Facade, Dependency Injection
- Teknik seam & wrapping kode lama (*Working Effectively with Legacy Code*)
- Feature flag & shadow/parallel run execution
- API design: kontrak yang stabil, semantik backward compatibility, versioning

#### 2. Soft Skill
- *Restraint* — menahan godaan "sekalian rapihin semuanya" yang berisiko memperlebar blast radius
- Manajemen risiko: memilah dengan tegas perubahan perilaku (*behavioral change*) vs perubahan kosmetik (*refactoring*)
- Negosiasi scope dengan Product Manager: *"Fitur ini bisa dirilis, tetapi lewat jalur aman yang memerlukan isolasi ekstra."*

#### 3. Knowledge Fundamental yang Diperlukan (Prerequisites)
- **SOLID & Modular Design Principles:** Terutama *Open/Closed Principle* (terbuka untuk ekstensi, tertutup untuk modifikasi) dan *Dependency Inversion Principle*.
- **Coupling & Cohesion Theory:** Memahami Afferent Coupling ($Ca$), Efferent Coupling ($Ce$), dan bagaimana coupling tinggi meningkatkan kerapuhan (*fragility*).
- **Contract & Semantic Invariants:** Teori *Design by Contract* (Pre-conditions, Post-conditions, Invariants).

#### 4. Knowledge Fundamental yang Dihasilkan / Didapat (Acquired)
- **Seam Theory in Practice (Michael Feathers):** Pemahaman fundamental bahwa setiap kode—seburuk apa pun—selalu memiliki titik sambung (*seam*) di mana perilaku bisa diubah atau disisipkan tanpa mengedit file sumber aslinya.
- **Mutation Containment & Blast Radius Calculus:** Intuisi mendalam dalam memperkirakan sejauh mana sebuah perubahan kode kecil akan merambat dan merusak bagian terjauh dari sistem.
- **Branching by Abstraction:** Pemahaman bahwa isolasi fitur baru jauh lebih aman dilakukan di level abstraksi kode runtime (via flag atau interface) daripada mengandalkan branch Git jangka panjang yang menyiksa saat merge.

---

### Kategori D — Database & Migrasi Data

> **Konteks:** Mengubah struktur skema, tipe data, dan memindahkan jutaan baris data pada sistem yang sedang aktif melayani transaksi 24/7.

#### 1. Hard Skill
- SQL tingkat menengah–lanjut: EXPLAIN, indexing strategy, query plan optimization
- Pola migrasi aman: Expand–Contract, backfill per batch, online schema change (pt-online-schema-change, gh-ost)
- ORM internals: eager vs lazy loading, N+1 query problem, hydration overhead
- Pemahaman transaction & isolation level
- Data cleansing & audit agregasi kualitas data

#### 2. Soft Skill
- Kehati-hatian ekstrem — kesalahan manipulasi data di produksi sering kali tidak bisa di-*undo*
- Perencanaan bertahap & disiplin menunggu (*"Tunggu 3 minggu sebelum menghapus kolom lama"*)
- Koordinasi intensif dengan tim bisnis untuk mengambil keputusan atas data ambigu/inkonsisten

#### 3. Knowledge Fundamental yang Diperlukan (Prerequisites)
- **Storage Engine & Index Mechanics:** Struktur B-Tree, B+Tree, LSM-Tree, Clustered vs Secondary Index, Table Scan vs Index Scan, Page reads.
- **ACID & Transaction Isolation Levels:** Fenomena anomali baca: *Dirty Read, Non-repeatable Read, Phantom Read*, serta level isolasi: *Read Uncommitted, Read Committed, Repeatable Read, Serializable*.
- **Locking & Concurrency Engine:** Shared Lock (S), Exclusive Lock (X), Intention Locks, Row-level lock vs Table-level lock, Lock escalation, Deadlock detection.
- **Write-Ahead Logging (WAL) & Durability:** Bagaimana DB menjamin durabilitas transaksi sebelum data fisik ditulis ke storage disk.

#### 4. Knowledge Fundamental yang Dihasilkan / Didapat (Acquired)
- **Zero-Downtime Expand-Contract Dynamics:** Pemahaman bahwa transformasi skema sistem hidup harus selalu melalui siklus 5 fase:
  1. *Expand:* Tambah kolom/tabel baru (nullable).
  2. *Dual-write:* Tulis ke lokasi lama dan baru bersamaan.
  3. *Backfill:* Salin data historis secara bertahap di background.
  4. *Read switch:* Alihkan pembacaan ke lokasi baru.
  5. *Contract:* Hapus kolom/tabel lama setelah aman.
- **Asynchronous Data Consistency Models:** Memahami batasan *strong consistency* vs *eventual consistency* saat menyinkronkan data antar tabel besar tanpa mengunci sistem.
- **The Reality of ORM Leaky Abstractions:** Kesadaran bahwa ORM menyembunyikan realitas mesin DB; performa tinggi hanya bisa dicapai bila developer paham representasi fisik data di balik abstraksi objek.

---

### Kategori E — Testing di Sistem Tanpa Test

> **Konteks:** Membangun jaring pengaman (*safety net*) otomatis pada basis kode raksasa yang belum pernah memiliki unit test sejak pertama kali dibuat.

#### 1. Hard Skill
- Characterization/snapshot testing (mengunci perilaku aktual, baik atau buruk)
- Test framework & test double (mock, stub, fake, spy, fixture)
- Integration testing dengan DB nyata (container/testcontainers, transaction rollback per test case)
- CI/CD setup (pipeline test otomatis, wajib hijau, test coverage reporting)
- Teknik sanitasi & anonimisasi data production untuk lingkungan test

#### 2. Soft Skill
- Pragmatisme — mulai dari coverage kecil yang paling berisiko tinggi (*critical business path*), bukan mengejar angka 100%
- Memengaruhi kebiasaan tim tanpa memaksa (ciptakan template test yang mudah dan cepat dijalankan dulu, baru jadikan syarat PR)
- Konsistensi jangka panjang — manfaat testing di legacy baru benar-benar terasa setelah berbulan-bulan

#### 3. Knowledge Fundamental yang Diperlukan (Prerequisites)
- **Test Double Taxonomy (Gerard Meszaros):** Perbedaan esensial antara Dummy, Stub, Spy, Mock, dan Fake, serta kapan masing-masing tepat digunakan.
- **Testing Pyramid & Feedback Loop Speed:** Alasan ekonomis di balik proporsi Unit, Integration, dan End-to-End Test, serta dampak durasi feedback loop terhadap produktivitas developer.
- **Hermetic Testing & State Isolation:** Prinsip bahwa setiap test harus independen, deterministik (*no flaky test*), dan membersihkan state-nya sendiri.

#### 4. Knowledge Fundamental yang Dihasilkan / Didapat (Acquired)
- **Characterization Testing Principles:** Penyadaran bahwa tujuan awal menguji legacy code *bukan untuk memverifikasi kebenaran (correctness)*, melainkan *mengunci perilaku saat ini (current behavior)* agar perubahan di masa depan tidak menimbulkan kejutan yang tidak diinginkan.
- **Testability as Design Feedback:** Menyadari bahwa kesulitan menulis test untuk sebuah kelas/fungsi bukan masalah framework test-nya, melainkan sinyal fundamental bahwa desain kode tersebut memiliki coupling terlalu tinggi (*tight coupling*) dan menyalahi *Single Responsibility Principle*.
- **Risk-Reward of Test Coverage:** Pemahaman strategis bahwa 20% test coverage pada jalur transaksi uang memberikan nilai keamanan 80% lebih besar daripada 90% test coverage pada utility helper.

---

### Kategori F — Upgrade Framework, Bahasa & Dependency

> **Konteks:** Menaikkan versi bahasa pemrograman (misal PHP 5.6 ke 8.x, Python 2 ke 3, Node lama ke LTS) atau framework yang sudah mencapai *End-of-Life* (EOL).

#### 1. Hard Skill
- Static analysis (PHPStan, mypy, TypeScript, ESLint) & automated refactoring (Rector, codemods)
- Dependency management & audit keamanan (lockfile internals, SemVer constraints, vulnerability scanning/CVE)
- CI matrix / containerization untuk menjalankan dua versi runtime secara berdampingan
- Membaca changelog & migration guide teknis secara efisien

#### 2. Soft Skill
- Perencanaan proyek panjang yang dicicil bertahap di sela-sela pengerjaan tiket fitur bisnis
- Komunikasi risiko ke manajemen (menerjemahkan kenapa upgrade adalah investasi kelangsungan hidup bisnis, bukan sekadar hobi teknis)
- Ketekunan & daya tahan mental — menghadapi ribuan error kompilasi/analisis statis yang repetitif

#### 3. Knowledge Fundamental yang Diperlukan (Prerequisites)
- **Semantic Versioning (SemVer) Mechanics:** Makna formal `MAJOR.MINOR.PATCH` serta aturan kontrak breaking change.
- **Abstract Syntax Tree (AST):** Bagaimana kode sumber di-parse oleh parser menjadi token, tree, dan bagaimana AST bisa dimanipulasi secara programatis.
- **Language Runtime & Garbage Collection Internals:** Perubahan mekanis antar-versi bahasa (misal: perubahan memory allocator, tipe data strict, model thread, atau engine JIT).

#### 4. Knowledge Fundamental yang Dihasilkan / Didapat (Acquired)
- **Dependency Graph Topology & Supply Chain Risks:** Pemahaman mendalam tentang *transitive dependencies* (library yang dipakai oleh library yang kita pakai) dan bagaimana satu titik rapuh di kedalaman dependency tree dapat melumpuhkan seluruh aplikasi.
- **Automated Refactoring at Scale:** Pemahaman bahwa refactoring ribuan file secara manual adalah resep bencana; otomatisasi berbasis AST (codemods) adalah satu-satunya cara deterministik untuk upgrade skala besar.
- **Compounding Technical Debt Economics:** Kesadaran matematis bahwa menunda upgrade versi satu kali lipat akan melipatgandakan biaya dan risiko upgrade di masa mendatang secara eksponensial.

---

### Kategori G — Modernisasi Bertahap & Arsitektur

> **Konteks:** Mengurai monolit tua yang kusut menjadi komponen modular atau microservices baru tanpa mematikan bisnis yang sedang berjalan.

#### 1. Hard Skill
- Pola arsitektur modernisasi: Strangler Fig, API Gateway, Anti-Corruption Layer (ACL), Outbox Pattern
- Routing & traffic splitting (reverse proxy, Nginx, Envoy, Canary release)
- Sinkronisasi data & konsistensi antar-sistem (CDC / Change Data Capture, Kafka, Debezium)
- Observability untuk membandingkan metrik sistem lama vs sistem baru (*diffing/shadowing*)

#### 2. Soft Skill
- Visi jangka panjang + kemampuan memecah visi multi-tahun menjadi milestone 2-mingguan
- Menjaga kepercayaan stakeholder — mendemonstrasikan value bisnis kecil di setiap sprint agar proyek tidak dihentikan di tengah jalan
- Pengambilan keputusan dalam ketidakpastian informasi
- Tahu kapan harus berhenti — bijak menyadari bahwa tidak semua bagian sistem legacy layak dimodernisasi

#### 3. Knowledge Fundamental yang Diperlukan (Prerequisites)
- **Distributed Systems Foundations & CAP Theorem:** Memahami tradeoff tak terhindarkan antara Consistency, Availability, dan Partition Tolerance.
- **Fallacies of Distributed Computing:** Memahami bahwa jaringan tidak pernah dapat diandalkan, latensi tidak pernah nol, dan bandwidth tidak pernah tak terbatas.
- **Enterprise Integration Patterns (EIP):** Konsep Message Channel, Pipes & Filters, Message Broker, Pub/Sub, dan Request-Reply.

#### 4. Knowledge Fundamental yang Dihasilkan / Didapat (Acquired)
- **Evolutionary Architecture Theory:** Arsitektur yang sukses bukanlah cetak biru statis yang sempurna di awal (*upfront design*), melainkan sistem yang dirancang untuk mudah diubah dan berevolusi seiring waktu (*fitness functions*).
- **The Dual-Write Hazard & Distributed Data Integrity:** Pemahaman bahwa melakukan penulisan ke dua database/sistem berbeda secara bersamaan tanpa mekanisme konsistensi khusus (seperti Outbox Pattern atau 2PC) pasti akan berakhir pada desinkronisasi data fatal.
- **Pragmatic Bounded Contexts:** Kemampuan mendefinisikan batasan modul/service berdasarkan alur transaksi bisnis nyata (*business domain events*), bukan sekadar memisahkan berdasarkan tabel DB teknis.

---

### Kategori H — Keamanan di Sistem Lama

> **Konteks:** Menambal lubang keamanan kritis (security vulnerabilities) pada sistem lawas yang dibangun di era sebelum standar keamanan modern diterapkan.

#### 1. Hard Skill
- OWASP Top 10 praktis: SQL Injection, Insecure Direct Object References (IDOR), XSS, Broken Authentication, SSRF
- Kriptografi terapan: bcrypt, argon2, salt, rotasi secret, secret manager, HTTPS/TLS
- Security tooling: SAST (Static Application Security Testing), DAST, secret scanning di Git repo
- Teknik migrasi aman: Rehash-on-login (mengganti hash MD5/SHA1 usang saat user sign in), enkripsi data at-rest

#### 2. Soft Skill
- Prioritisasi berbasis risiko (menghitung skor $Severity \times Exposure$, bukan panik membenahi semua hal sekaligus)
- Kerahasiaan & etika — temuan celah keamanan ditangani secara bertanggung jawab (*responsible disclosure*)
- Ketegasan (*assertiveness*) — mengadvokasi prioritas keamanan di hadapan tim produk yang sering kali lebih menginginkan fitur visual

#### 3. Knowledge Fundamental yang Diperlukan (Prerequisites)
- **CIA Triad & Defense in Depth:** Prinsip Confidentiality, Integrity, Availability, serta konsep pertahanan berlapis (keamanan di network, OS, aplikasi, dan data).
- **Cryptographic Primitives & Entropy:** Perbedaan mendasar antara Enkripsi (simetris/asimetris), Hashing, Encoding, dan Digital Signature, serta bahaya menggunakan hash cepat (MD5/SHA) untuk password.
- **Authentication, Authorization & Accounting (AAA):** Mekanisme identifikasi identitas vs hak akses pada resource, serta audit trail logging.

#### 4. Knowledge Fundamental yang Dihasilkan / Didapat (Acquired)
- **Threat Surface Expansion Dynamics:** Kesadaran bahwa sistem legacy memiliki permukaan serangan (*attack surface*) yang jauh lebih besar karena banyaknya endpoint uji coba lama, fitur terbengkalai, dan port terbuka yang terlupakan.
- **Inline Cryptographic Migration:** Pemahaman mekanisme transisi data sensitif secara live tanpa downtime (contoh: membaca hash lama, memverifikasi, meng-hash ulang dengan algoritma baru, dan menyimpan kembali ke DB secara transparan).
- **Security as an Operational Constraint:** Menyadari bahwa sistem keamanan yang terlalu rumit dan menghambat operasional bisnis hanya akan memicu karyawan mencari *workaround* berbahaya; sistem keamanan harus dirancang selaras dengan kenyamanan kerja.

---

### Kategori I — Incident & Production

> **Konteks:** Menangani situasi darurat saat aplikasi produksi down, mengalami lonjakan latensi, kehabisan resource, atau corrupt di tengah jam sibuk.

#### 1. Hard Skill
- Incident response: triase cepat, mitigasi prioritas utama (rollback, restart, scale out, rate limit), investigasi akar masalah belakangan
- Observability stack: structured logging, APM dashboard, metric alerting, tracing
- Deployment engineering: canary/blue-green release, mekanisme rollback instan, runbook operasional
- Queue internals: handling dead-letter queue (DLQ), idempotency, backpressure management

#### 2. Soft Skill
- Ketenangan di bawah tekanan (*calm under pressure*) — pembeda terbesar antara engineer senior dan junior saat insiden terjadi
- Komunikasi krisis: memberikan status update berkala kepada manajemen dan user meskipun akar masalah belum sepenuhnya teridentifikasi
- Budaya *Blameless Post-Mortem*: fokus mengevaluasi kegagalan sistem dan prosedur, bukan mencari kambing hitam
- Disiplin aksi tindak lanjut (*action items*): konsisten menyelesaikan perbaikan pencegahan setelah insiden selesai

#### 3. Knowledge Fundamental yang Diperlukan (Prerequisites)
- **Operating System Resource Contention:** CPU Scheduling, Memory Paging, Out-Of-Memory (OOM) Killer, File Descriptor exhaustion, Epoll/Socket lifecycle.
- **Observability Telemetry Models:** Konsep RED Method (*Rate, Errors, Duration*) untuk request-driven system, dan USE Method (*Utilization, Saturation, Errors*) untuk resources.
- **Resilience Engineering Patterns:** Circuit Breaker, Exponential Backoff with Jitter, Bulkhead, Throttling/Rate Limiting.

#### 4. Knowledge Fundamental yang Dihasilkan / Didapat (Acquired)
- **Normal Accidents Theory (Charles Perrow):** Pemahaman fundamental bahwa dalam sistem yang memiliki kompleksitas tinggi dan keterikatan ketat (*tight coupling*), kegagalan berganda yang tak terduga adalah hal yang wajar dan niscaya terjadi.
- **Triage Dynamics (Mitigation vs Remediation):** Kemampuan mental memisahkan dengan tegas antara tindakan *menghentikan pendarahan* (mitigasi: isolasi traffic, kurangi beban) dengan tindakan *menyembuhkan luka* (remediasi: code fix jangka panjang).
- **Human Factors in High-Stress Environments:** Pemahaman bahwa dalam situasi krisis, kapasitas kognitif manusia menurun drastis; itulah mengapa runbook tertulis yang jelas jauh lebih berharga daripada kejeniusan spontan.

---

### Kategori J — Kolaborasi, Komunikasi & Kepemimpinan

> **Konteks:** Menjembatani kepentingan teknis masa lalu, masa kini, dan masa depan dengan realitas bisnis, manajemen, serta dinamika antar-manusia di dalam tim.

#### 1. Hard Skill
- Menerjemahkan *technical debt* ke dalam metrik finansial/bisnis (jam terbuang, biaya operasional insiden, risiko turn-over engineer)
- Estimasi kapasitas & alokasi resource (contoh: model alokasi 20% kapasitas per sprint khusus untuk technical debt)
- Menulis dokumen teknis strategis: panduan review, playbook insiden, runbook, arsitektur masa depan, dan materi onboarding

#### 2. Soft Skill
- Empati mendalam — kepada developer lama yang menulis kode tersebut, kepada developer baru yang kebingungan, dan kepada manajemen yang memiliki tekanan target
- Persuasi berbasis data, bukan perdebatan opini selera teknologi
- Mentoring & pairing: mendampingi engineer lain agar tidak takut menyentuh modul-modul kritis
- Kerendahan hati (*humility*) menerima bahwa keputusan teknis yang "jelek" saat ini mungkin adalah keputusan paling rasional yang bisa diambil pada masa lalu dengan segala keterbatasannya
- Membangun budaya engineering lewat teladan (*action*), bukan sekadar aturan atau teguran

#### 3. Knowledge Fundamental yang Diperlukan (Prerequisites)
- **Conway’s Law:** Struktur sistem software yang dibangun oleh suatu organisasi secara alamiah akan menduplikasi struktur komunikasi di dalam organisasi tersebut.
- **Economics of Software Engineering:** Konsep Opportunity Cost, Total Cost of Ownership (TCO), Capital Expenditure (CapEx) vs Operational Expenditure (OpEx), serta suku bunga hutang teknis (*technical debt interest*).
- **Cognitive Load Theory:** Batasan kapasitas memori kerja manusia saat mencerna kode; bagaimana arsitektur yang buruk membebani kapasitas kognitif tim dan memperlambat delivery.

#### 4. Knowledge Fundamental yang Dihasilkan / Didapat (Acquired)
- **Technical Diplomacy:** Seni mengomunikasikan realitas sistem software ke tingkat eksekutif dengan bahasa risiko, nilai bisnis, dan kontinuitas operasional, bukan istilah teknis rumit.
- **Governance through "Paved Roads":** Pemahaman bahwa cara terbaik mengubah kebiasaan buruk tim bukan dengan melarang atau membuat SOP kaku, melainkan dengan menyediakan *tools dan template yang membuat jalur benar menjadi jalur yang paling mudah diambil*.
- **Historical Empathy & Software Pragmatism:** Kedewasaan profesional untuk berhenti mencela kode lama sebagai "sampah", dan menyadari bahwa kode legacy itulah yang selama ini mendanai gaji seluruh karyawan dan membuktikan produk tersebut memiliki nilai pasar nyata (*product-market fit*).

---

## 📚 Kurasi Buku Wajib & Referensi Utama per Kategori

Untuk memperdalam *Knowledge Fundamental* dan *Hard Skill* di sistem legacy, berikut adalah daftar buku standar industri dunia yang paling esensial, dikelompokkan berdasarkan prioritas dan relevansi kategorinya:

### 🏆 "The Holy Trinity" (3 Buku Wajib Paling Utama)
Jika harus memilih 3 buku saja untuk dibaca pertama kali:
1. **"Working Effectively with Legacy Code" — Michael Feathers**
   - *Fokus:* Kitab suci penanganan legacy code. Buku yang memperkenalkan konsep *Seam*, *Characterization Test*, *Sprout Method/Class*, dan cara membedah dependency tanpa merusak sistem. Wajib untuk Kategori **A, B, C, E**.
2. **"Designing Data-Intensive Applications (DDIA)" — Martin Kleppmann**
   - *Fokus:* Buku terbaik di dunia tentang cara kerja data engine. Membahas B-Tree vs LSM, transaksi & isolation level (phantom/dirty read), replikasi, dual-write hazard, konsistensi data, dan event-driven architecture. Wajib untuk Kategori **D, G**.
3. **"Release It!: Design and Deploy Production-Ready Software" — Michael T. Nygard**
   - *Fokus:* Arsitektur ketahanan sistem di produksi. Membahas *Circuit Breaker*, *Bulkhead*, *Timeouts*, *Cascading Failures*, penanganan thread pool, dan stabilitas operasional saat sistem terbakar. Wajib untuk Kategori **A, I**.

---

### 📖 Pemetaan Buku Spesifik per Kategori (A — J)

#### Kategori A — Bug Fix di Sistem Rapuh
- **"Why Programs Fail: A Guide to Systematic Debugging" — Andreas Zeller**  
  *Mengapa penting:* Membedah metodologi ilmiah debugging (hipotesis, isolasi penyebab, delta debugging, trace comparison), bukan tebak-tebak berhadiah.
- **"Release It!" — Michael T. Nygard** (Bab: *Stability Patterns & Anti-patterns*)  
  *Mengapa penting:* Menjelaskan mekanika kegagalan sistematis (thread pool exhaustion, unbounded queue, slow responses).

#### Kategori B — Memahami & Mendokumentasikan Sistem
- **"Your Code as a Crime Scene" — Adam Tornhill**  
  *Mengapa penting:* Menggunakan analisis data Git forensics untuk mendeteksi modul kode mana yang paling sering berubah, paling rumit, dan paling rawan bug (*hotspots*).
- **"Software Architecture: The Hard Parts" — Neal Ford, Mark Richards, Pramod Sadalage, Zhamak Dehghani**  
  *Mengapa penting:* Memberikan metodologi analisis tradeoff arsitektur dan cara memetakan sistem monolit yang kusut secara objektif.

#### Kategori C — Menambah Fitur Tanpa Merusak
- **"Working Effectively with Legacy Code" — Michael Feathers**  
  *Mengapa penting:* Menjelaskan teknik menyisipkan fitur baru dengan pola *Wrap Method*, *Sprout Class*, dan *Branch by Abstraction*.
- **"Refactoring: Improving the Design of Existing Code (2nd Edition)" — Martin Fowler**  
  *Mengapa penting:* Katalog langkah-langkah mikro yang aman untuk mengubah struktur internal tanpa mengubah perilaku eksternal kode.

#### Kategori D — Database & Migrasi Data
- **"Designing Data-Intensive Applications" — Martin Kleppmann** (Bab 3, 7, 9: Storage, Transactions, Consistency)  
  *Mengapa penting:* Memahami detail mekanik database engine, lock contention, dan transaksi ACID.
- **"Refactoring Databases: Evolutionary Database Design" — Scott J. Ambler & Pramod J. Sadalage**  
  *Mengapa penting:* Pola resmi migrasi database tanpa downtime (*Expand-Contract*, *Transition Period*, migrasi skema paralel).
- **"Database Reliability Engineering (DBRE)" — Laine Campbell & Charity Majors**  
  *Mengapa penting:* Praktik merawat, mengamankan, dan mengoperasikan database produksi berskala besar.

#### Kategori E — Testing di Sistem Tanpa Test
- **"Unit Testing: Principles, Practices, and Patterns" — Vladimir Khorikov**  
  *Mengapa penting:* Buku testing paling modern dan pragmatis. Membedah kapan harus menggunakan mock vs state verification, serta cara menguji tanpa terjebak *brittle tests*.
- **"Working Effectively with Legacy Code" (Bab 13 & 24: *Characterization Tests* & *How Do I Know I'm Not Breaking Anything?*)**  
  *Mengapa penting:* Fondasi bagaimana menulis test untuk mengunci perilaku sistem saat ini tanpa harus tahu apakah perilakunya "benar" menurut spesifikasi awal.

#### Kategori F — Upgrade Framework, Bahasa & Dependency
- **"Software Engineering at Google: Lessons Learned from Programming Over Time" — Titus Winters, Tom Manshreck, Hyrum Wright**  
  *Mengapa penting:* Membahas *Hyrum's Law*, konsep *Deprecation*, dan bagaimana perusahaan raksasa mengelola *Large-Scale Changes (LSC)* dan migrasi dependensi otomatis.

#### Kategori G — Modernisasi Bertahap & Arsitektur
- **"Monolith to Microservices: Evolutionary Patterns to Transform Your Monolith" — Sam Newman**  
  *Mengapa penting:* Panduan paling praktis memecah monolit. Membahas pola *Strangler Fig*, *Change Data Capture (CDC)*, *Outbox Pattern*, dan pemisahan database bertahap.
- **"Building Evolutionary Architectures" — Neal Ford, Rebecca Parsons, Patrick Kua**  
  *Mengapa penting:* Konsep *Architectural Fitness Functions* untuk memastikan modernisasi arsitektur tidak kembali menjadi legacy yang buruk.

#### Kategori H — Keamanan di Sistem Lama
- **"Threat Modeling: Designing for Security" — Adam Shostack**  
  *Mengapa penting:* Mengajarkan cara berpikir seperti peretas (*attacker*) untuk mengidentifikasi celah pada sistem yang sudah ada dengan metode STRIDE.
- **"Alice and Bob Learn Application Security" — Tanya Janca**  
  *Mengapa penting:* Panduan praktis implementasi keamanan aplikasi: otentikasi, otorisasi, sanitasi input, dan penanganan kriptografi modern untuk developer.

#### Kategori I — Incident & Production
- **"Site Reliability Engineering (SRE): How Google Runs Production Systems" — Betsy Beyer et al.**  
  *Mengapa penting:* Standar emas industri untuk manajemen insiden, *Blameless Post-mortem*, *Error Budgets*, dan triase sistem produksi.
- **"Observability Engineering: Achieving Achievable Systems" — Charity Majors, Liz Fong-Jones, George Miranda**  
  *Mengapa penting:* Memahami telemetri modern: mengapa structured events & high cardinality data jauh lebih ampuh daripada traditional metrics & unformatted logs untuk investigasi downtime.

#### Kategori J — Kolaborasi, Komunikasi & Kepemimpinan
- **"Kill It with Fire: Manage Aging Computer Systems (and Future Proof Modern Ones)" — Marianne Bellotti**  
  *Mengapa penting:* Buku luar biasa yang khusus ditulis tentang *seni memodernisasi sistem legacy raksasa*. Menyoroti aspek psikologi organisasi, politik anggaran, dinamika tim, dan empati terhadap sistem lama.
- **"Team Topologies: Organizing Business and Technology Teams for Fast Flow" — Matthew Skelton & Manuel Pais**  
  *Mengapa penting:* Memahami *Conway's Law* dan *Cognitive Load*; bagaimana struktur tim mempengaruhi kerapuhan arsitektur software dan cara mengatasinya.

---

## 💡 Ringkasan Filosofis: Dari Coder Menjadi System Steward

Seorang backend developer di sistem legacy bukan sekadar seseorang yang menulis fungsi dan query. Mereka berevolusi menjadi seorang **System Steward (Penjaga Sistem)**:
1. **Memahami Masa Lalu:** Mampu membaca sejarah keputusan bisnis dan kompromi teknis yang membeku dalam bentuk kode sumber.
2. **Mengamankan Masa Kini:** Menjaga agar sistem yang rapuh tetap menghasilkan uang, melayani pengguna, dan stabil di bawah tekanan operasional harian.
3. **Membuka Jalan ke Masa Depan:** Mencicil transformasi sistem secara elegan—tanpa memutus aliran bisnis—menuju arsitektur yang lebih sehat dan terawat.
