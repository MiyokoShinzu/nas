<?php include './globals/head.php'; ?>

<body class="bg-light">
    <?php include './globals/nav.php'; ?>
    <div class="row " style="height: 85vh; background: #fff; overflow: hidden;">
        <canvas id="network-bg"></canvas>
        <div data-aos="fade-down" class="col-11 col-lg-7 mx-auto d-flex align-items-center justify-content-center flex-column">
            <div class="masking-container text-center">
                <h1 class="masked-text">NɅS CLUB</h1>
                <style>
                    .masked-text {
                        font-size: 8rem;
                        font-weight: bold;
                        color: transparent;
                        background-image: url('https://static.vecteezy.com/system/resources/thumbnails/017/446/303/small/technology-abstract-lines-and-dots-connection-background-connection-digital-data-and-big-data-concept-network-connection-dots-and-lines-futuristic-background-illustration-vector.jpg');
                        background-size: 200%;
                        background-position: 0 50%;
                        background-clip: text;
                        -webkit-background-clip: text;
                        -webkit-text-fill-color: transparent;
                        animation: animate-background 5s infinite alternate linear;
                    }

                    @keyframes animate-background {
                        0% {
                            background-position: 0 50%;
                        }

                        100% {
                            background-position: 100% 50%;
                        }
                    }
                </style>
                <style>
                    #network-bg {
                        position: absolute;
                        inset: 0;
                        z-index: 0;
                        pointer-events: none;
                        display: block;
                    }

                    #reg_btn {
                        transition: all 0.3s ease;
                    }

                    #reg_btn:hover {
                        box-shadow: 1px 1px 14px var(--bs-purple)
                    }
                </style>
            </div>
            <p class="lead mb-4 text-center">Welcome to our official club page. Join us!</p>
            <div class="links">
                <a href="#about" class="btn btn-primary btn-lg me-2">About Us</a>
                <a href="activities.html" class="btn  btn-lg" style="background: #af18f0ff; color: #fff;" data-bs-toggle="modal" data-bs-target="#reg_mdl" id="reg_btn">Register Now</a>
            </div>
        </div>
    </div>
    <div class="row mt-5" id="about">
        <div class="col-11 col-lg-7 mb-2 mx-auto d-flex align-items-center justify-content-center flex-column p-3">
            <h3 class="article-title">General Objectives</h3>
            <p class="article-intro">The NAS Club is dedicated to foster technical growth and collaborative learning among Computer Engineering students through activities that combine theory with practical applications. In line with the vision and mission of the organization, the club aims to equip its members with essential skills in networking, data storage, and system administration while also contributing to the academic and community initiatives of the college. To achieve this, the NAS Club sets forth the following objectives:</p>
            <ul>
                <li>Conduct introductory workshops on the basics of NAS, networking, and data storage for all members.</li>
                <li>Establish a functional NAS system within the club as a shared learning platform.</li>
                <li>Organize monthly technical sessions where senior students mentor juniors on hardware, software, and system configuration.</li>
                <li>Launch at least one major project showcasing the practical applications of NAS.</li>
                <li>Design and maintain documentation and learning resources for continuity and knowledge transfer across year levels.</li>
                <li>Participate in or prepare for CPE-related events, competitions, or research activities related to networking and storage.</li>
                <li>Strengthen teamwork and leadership by assigning officer roles and project responsibilities to members from 1st to 4th year.</li>
                <li>Actively participate in college research and extension projects, applying NAS-related knowledge and skills to academic and community initiatives.</li>
            </ul>
        </div>
        <div class="col-11 col-lg-7 mb-2 mx-auto d-flex align-items-center justify-content-center flex-column p-3">
            <h3 class="article-title">Mission</h3>
            <p class="article-intro">The NAS Club empowers Computer Engineering students to gain hands-on experience in storage technologies and networking, fostering collaboration, innovation, and leadership to prepare them for future careers in IT and engineering</p>
        </div>
        <div class="col-11 col-lg-7 mb-2 mx-auto d-flex align-items-center justify-content-center flex-column p-3">
            <h3 class="article-title">Vision</h3>
            <p class="article-intro">To cultivate future-ready Computer Engineers by fostering knowledge and hands-on expertise in Network Attached Storage (NAS), networking, and data systems, empowering students to design, build, and manage innovative storage solutions for the digital age.</p>
        </div>
    </div>
    <div class="row mt-5 bg-light text-dark" id="footer">
        <div class="col-lg-12">

        </div>
        <div class="row col-lg-7 col-12 mx-auto p-3 d-flex align-items-center justify-content-center">
            <div class="col-md-4" style="display: flex; align-items: center; justify-content: center; flex-direction: column">
                <img class="mb-2" src="./assets/csu.png" alt="" style="height: 100px; width: 100px; ">
                <p class="border-bottom border-primary border-2 text-center pb-2">Cagayan State University Carig Campus</p>
            </div>
            <div class="col-md-4" style="display: flex; align-items: center; justify-content: center; flex-direction: column">
                <img class="mb-2" src="./assets/coea.png" alt="" style="height: 100px; width: 100px;">
                <p class="border-bottom border-primary border-2 text-center pb-2">College of Engineering and Architecture</p>
            </div>

            <div class="col-md-4" style="display: flex; align-items: center; justify-content: center; flex-direction: column">
                <img class="mb-2" src="./assets/icpep.png" alt="" style="height: 100px; width: 100px; ">
                <p class="border-bottom border-primary border-2 text-center pb-2">Institute of Computer Engineers of the Philippines</p>
            </div>
        </div>
    </div>
    <style>
        .form-control, .form-select {
            color: var(--bs-purple)
        }

        .form-control:focus {
            border-color: #6f42c1;
            /* Purple border */
            box-shadow: 0 0 0 0.25rem rgba(111, 66, 193, 0.25);
            /* Purple glow */

        }

        .form-select:focus {
            border-color: #6f42c1;
            /* Purple border */
            box-shadow: 0 0 0 0.25rem rgba(111, 66, 193, 0.25);
            /* Purple glow */
        }
    </style>
    <div class="modal fade" id="reg_mdl" tabindex="-1" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background: var(--bs-purple); color: #fff;">
                    <h5 class="modal-title" id="modalTitleId">Pre-registration Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid row">
                        <div class="form-group mb-3 col-md-4">
                            <label class="text-dark fw-bold mb-1" for="formId1">First Name</label>
                            <input type="text" class="form-control" name="firstname" id="firstname" placeholder="" required />
                        </div>
                        <div class="form-group mb-3 col-md-4">
                            <label class="text-dark fw-bold mb-1" for="formId1">Middle Name</label>
                            <input type="text" class="form-control" name="middlename" id="middlename" placeholder="" required />
                        </div>
                        <div class="form-group mb-3 col-md-4">
                            <label class="text-dark fw-bold mb-1" for="formId1">Last Name</label>
                            <input type="text" class="form-control" name="lastname" id="lastname" placeholder="" required />
                        </div>
                        <div class="form-group mb-3 col-md-4">
                            <label class="text-dark fw-bold mb-1" for="formId1">Suffix</label>
                            <input type="text" class="form-control" name="suffix" id="suffix" placeholder="" />
                            <small style="font-size: 10px; color: var(--bs-purple); font-weight: bold; margin: 0; padding: 0;">Leave blank if you do not have a name suffix (e.g., Jr., Sr., III)</small>
                        </div>
                        <div class="form-group mb-3 col-md-4">
                            <label class="text-dark fw-bold mb-1" for="formId1">Year</label>
                            <select name="year" id="year" class="form-select" required>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                        </div>
                        <div class="form-group mb-3 col-md-4">
                            <label class="text-dark fw-bold mb-1" for="formId1">Section</label>
                            <select name="section" id="section" class="form-select" required>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                            </select>
                        </div>
                        <div class="form-group mb-3 col-md-6">
                            <label class="text-dark fw-bold mb-1" for="formId1">Email</label>
                            <input type="email" class="form-control" name="email" id="email" placeholder="example@gmail.com" required />
                        </div>
                        <div class="form-group mb-3 col-md-6">
                            <label class="text-dark fw-bold mb-1" for="formId1">Contact Number</label>
                            <input type="text" class="form-control" name="contact" id="contact" placeholder="0912......" required />
                        </div>

                        <div class="mb-3">
                            <label class="text-dark fw-bold mb-1" for="" class="form-label">Contribution/Suggestion</label>
                            <textarea class="form-control" name="" id="suggestion" rows="3" placeholder="Enter here your possible contributions or suggestions to the club." required></textarea>
                        </div>


                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button class="btn " style="background: var(--bs-purple); color: #fff;" id="register_btn">Save</button>
                </div>
            </div>
        </div>
    </div>

    <button id="backToTop" class="btn btn-primary">
        ↑
    </button>

    <script>
        var modalId = document.getElementById('modalId');
        modalId.addEventListener('show.bs.modal', function(event) {
            let button = event.relatedTarget;
            let recipient = button.getAttribute('data-bs-whatever');
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        AOS.init();
    </script>
    <script>
        window.addEventListener("scroll", function() {
            const navbar = document.querySelector(".navbar");
            if (window.scrollY > 50) {
                navbar.classList.add("scrolled");
            } else {
                navbar.classList.remove("scrolled");
            }
        });
    </script>
    <style>
        #backToTop {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: none;
            /* hidden by default */
            z-index: 999;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
            text-align: center;
            line-height: 1.2;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        }

        #backToTop:hover {
            background-color: #0a58ca;
            /* darker blue */
        }
    </style>

    <script>
        const backToTopBtn = document.getElementById("backToTop");

        window.addEventListener("scroll", function() {
            if (window.scrollY > 200) {
                backToTopBtn.style.display = "block";
            } else {
                backToTopBtn.style.display = "none";
            }
        });

        backToTopBtn.addEventListener("click", function() {
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        });
    </script>

    <script>
        function toast(message, bgCode) {
            const now = new Date();
            const timeString = now.toLocaleTimeString();

            const bgMap = {
                1: 'bg-success',
                2: 'bg-danger',
                3: 'bg-warning',
                4: 'bg-info',
                5: 'bg-purple'
            };
            const bgClass = bgMap[bgCode] || 'bg-secondary';

            const toastContainer = document.createElement('div');
            toastContainer.className = 'position-fixed bottom-0 end-0 p-3';
            toastContainer.style.zIndex = '9999';

            const toastHTML = `
        <div class='toast align-items-center text-white ${bgClass} border-0' role='alert'>
          <div class='d-flex'>
            <div class='toast-body'>
              ${message} at ${timeString}
            </div>
            <button type='button' class='btn-close btn-close-white me-2 m-auto' data-bs-dismiss='toast'></button>
          </div>
        </div>`;

            toastContainer.innerHTML = toastHTML;
            document.body.appendChild(toastContainer);

            const toastEl = toastContainer.querySelector('.toast');
            const toast = new bootstrap.Toast(toastEl);
            toast.show();

            toastEl.addEventListener('hidden.bs.toast', () => {
                toastContainer.remove();
            });
        }
    </script>
    <script>
        $(document).on('click', "#register_btn", function() {
            var firstname = $("#firstname").val();
            var middlename = $("#middlename").val();
            var lastname = $("#lastname").val();
            var suffix = $("#suffix").val();
            var year = $("#year").val();
            var section = $("#section").val();
            var email = $("#email").val();
            var contact = $("#contact").val();
            var suggestion = $("#suggestion").val();

            if (firstname == "" || middlename == "" || lastname == "" || year == "" || section == "" || email == "" || contact == "" || suggestion == "") {
                toast("Please fill out all the fields.", 2);
                return;
            } else {
                fetch(`./api/register.php?firstname=${firstname}&middlename=${middlename}&lastname=${lastname}&suffix=${suffix}&year=${year}&section=${section}&email=${email}&contact=${contact}&suggestion=${suggestion}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            // Delay in milliseconds
                            const delay = 2000; // Change this value or pass it dynamically
                            setTimeout(() => {
                              // Action to perform after delay
                              location.reload();
                            }, delay);
                          
                        }
                    })
                    .catch(err => console.error(err))
            }
            toast("Registered", 5);
        })
    </script>
    <script>
        (function() {
            const canvas = document.getElementById('network-bg');
            const ctx = canvas.getContext('2d');

            let width = 0,
                height = 0,
                DPR = Math.max(1, window.devicePixelRatio || 1);

            function resize() {
                const parent = canvas.parentElement;
                width = parent.clientWidth;
                height = parent.clientHeight;
                canvas.width = Math.floor(width * DPR);
                canvas.height = Math.floor(height * DPR);
                canvas.style.width = width + 'px';
                canvas.style.height = height + 'px';
                ctx.setTransform(DPR, 0, 0, DPR, 0, 0);
            }
            window.addEventListener('resize', resize, {
                passive: true
            });
            resize();

            const config = {
                nodeCount: 15,
                speed: 0.8,
                maxLinkDistance: 100
            };

            let nodes = [];

            function rand(min, max) {
                return Math.random() * (max - min) + min;
            }

            function createNodes() {
                nodes = [];
                for (let i = 0; i < config.nodeCount; i++) {
                    nodes.push({
                        x: rand(0, width),
                        y: rand(0, height),
                        vx: rand(-config.speed, config.speed),
                        vy: rand(-config.speed, config.speed),
                        r: rand(1, 7), // varying sizes
                        hue: rand(200, 250)
                    });
                }
            }

            function draw() {
                ctx.clearRect(0, 0, width, height);
                for (let i = 0; i < nodes.length; i++) {
                    const a = nodes[i];
                    for (let j = i + 1; j < nodes.length; j++) {
                        const b = nodes[j];
                        const dx = a.x - b.x,
                            dy = a.y - b.y,
                            d = Math.hypot(dx, dy);
                        if (d < config.maxLinkDistance) {
                            const alpha = 1 - (d / config.maxLinkDistance);
                            ctx.beginPath();
                            ctx.moveTo(a.x, a.y);
                            ctx.lineTo(b.x, b.y);
                            ctx.strokeStyle = `hsla(250, 70%, 65%, ${alpha * 0.4})`;
                            ctx.lineWidth = 1;
                            ctx.stroke();
                        }
                    }
                }

                // draw nodes
                for (const n of nodes) {
                    ctx.beginPath();
                    ctx.arc(n.x, n.y, n.r, 0, Math.PI * 2);
                    ctx.fillStyle = `hsl(${n.hue}, 80%, 60%)`;
                    ctx.fill();
                }
            }

            function step() {
                for (const n of nodes) {
                    n.x += n.vx;
                    n.y += n.vy;
                    if (n.x < 0 || n.x > width) n.vx *= -1;
                    if (n.y < 0 || n.y > height) n.vy *= -1;
                }
                draw();
                requestAnimationFrame(step);
            }

            createNodes();
            step();
        })();
    </script>
</body>

</html>