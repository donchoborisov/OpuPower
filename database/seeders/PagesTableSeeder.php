<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\User;
use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $authorId = User::where('email', env('ADMIN_EMAIL', 'admin@admin.com'))->value('id') ?? 1;

        $pages = [
       0 => 
            array (
                'id' => 2,
                'author_id' => 1,
                'title' => 'IT Support Services',
                'excerpt' => 'To ensure you get the quickest solution to network issues, you need to call a trusted network support provider. OPU POWER is solving headaches for businesses of all sizes. Our team begins working on your network issues from the moment you contact us.
OPU POWER bring the Companies and Customers together.',
                'body' => '<div class="container mb-10 px-4 mx-auto max-w-5xl">
<div class="text-center py-5 sm:py-14">
<h1 class="text-3xl md:text-5xl font-bold font-title text-sec">IT Network<br /><span class="text-pri"> Support Services </span></h1>
</div>
<hr />
<div class="text-center py-10 sm:py-14">
<h5 class="font-title text-gen opacity-70">To ensure you get the quickest solution to network issues, you need to call a trusted network support provider. <span class="text-sec">OPU</span> <span class="text-pri">POWER</span> is solving headaches for businesses of all sizes. Our team begins working on your network issues from the moment you contact us.<br /><span class="text-sec">OPU</span> <span class="text-pri">POWER</span> bring the Companies and Customers together.</h5>
</div>
<section class="main">
<div class=" grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-y-8 sm:gap-y-12 lg:gap-y-16 gap-x-8 lg:gap-x-12"><!-- first row -->
<div class="col-span-1 md:col-span-2 lg:col-span-3">
<div class=" block md:flex rounded-3xl overflow-hidden transition duration-300 ">
<div class="md:w-1/2"><img class="" src="/storage/pages/contact.jpg" alt="" width="400px" /></div>
<div class="md:w-1/2 p-4 md:p-7 my-8 ">
<div class=" mt-10  px-7 py-7 text-center rounded text-sec "><a href="tel:00447912040903"> <button class="px-12 shadow py-2 md:mr-10 rounded-3xl bg-sec text-white hover:bg-blue-800 focus:outline-none mt-4 sm:mt-0 sm:-ml-12" type="submit"> Call us </button></a></div>
<div class=" mt-3  px-7 py-7 text-center rounded text-sec "><a href="mailto:opupower@yahoo.com"> <button class="px-12 shadow py-2 md:mr-10 rounded-3xl bg-sec text-white hover:bg-blue-800 focus:outline-none mt-4 sm:mt-0 sm:-ml-12" type="submit"> Send E-mail </button></a></div>
</div>
</div>
</div>
<!-- end  --></div>
</section>
</div>',
                'image' => 'pages/February2022/yAWMgIsqmAbWhluBd0kL.jpg',
                'slug' => 'it-support-services',
                'meta_description' => 'IT Support',
                'meta_keywords' => 'IT Support',
                'status' => 'ACTIVE',
                'created_at' => '2022-02-14 11:49:05',
                'updated_at' => '2022-02-17 14:56:06',
            ),
            1 => 
            array (
                'id' => 3,
                'author_id' => 1,
                'title' => 'Network Installation',
                'excerpt' => 'OPU POWER provides a complete network design and installation service from the initial site survey and performance requirement determination, through cabling installation, network infrastructure hardware installation & configuration, server and client PC installation, client software and applications software installation, to the final commissioning, testing and documentation of the completed network installation..',
                'body' => '<div class="container px-4 mx-auto max-w-5xl">
<div class="text-center py-5 sm:py-14">
<h1 class="text-3xl md:text-5xl font-bold font-title text-sec">Network<br /><span class="text-pri"> Installation </span></h1>
</div>
<hr />
<div class="text-center py-10 sm:py-14">
<h5 class="font-title text-gen opacity-70"><span class="text-sec">OPU</span> <span class="text-pri">POWER</span> provides a complete network design and installation service from the initial site survey and performance requirement determination, through cabling installation, network infrastructure hardware installation &amp; configuration, server and client PC installation, client software and applications software installation, to the final commissioning, testing and documentation of the completed network installation..</h5>
</div>
<section class="main">
<div class=" grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-y-8 sm:gap-y-12 lg:gap-y-16 gap-x-8 lg:gap-x-12"><!-- first row -->
<div class="col-span-1 md:col-span-2 lg:col-span-3 ">
<div class=" bg-gradient-to-r from-purple-200 via-pink-400 to-red-300  border border-gray-100 block rounded-3xl overflow-hidden filter drop-shadow-lg  ">
<div class=" p-4 md:p-7 ">
<h2 class="font-bold text-sec text-xl md:text-2xl font-title py-3">Site Survey</h2>
<p class="text-gen pb-5">A cabling engineer will attend site and document the physical and environmental characteristics of the site and establish the network performance and cabling installation requirements.</p>
</div>
</div>
</div>
<!-- end  --> <!-- second row -->
<div class="col-span-1 md:col-span-2 lg:col-span-3">
<div class="bg-gradient-to-r from-purple-200 via-pink-400 to-red-300  border border-gray-100 block rounded-3xl overflow-hidden filter drop-shadow-lg  ">
<div class=" p-4 md:p-7 ">
<h2 class="font-bold text-sec text-xl md:text-2xl font-title py-3">Network Design</h2>
<p class="text-gen pb-5">Network topology and infrastructure hardware are specified to meet agreed performance and environmental requirements.</p>
</div>
</div>
</div>
<!-- end  --> <!-- third row -->
<div class="col-span-1 md:col-span-2 lg:col-span-3">
<div class="bg-gradient-to-r from-purple-200 via-pink-400 to-red-300  border border-gray-100 block rounded-3xl overflow-hidden filter drop-shadow-lg  ">
<div class=" p-4 md:p-7 ">
<h2 class="font-bold text-sec text-xl md:text-2xl font-title py-3">Network Cabling Installation</h2>
<p class="text-gen pb-5">In collaboration with selected partner companies, where necessary, supply and install structured cabling systems for data and voice if required.</p>
</div>
</div>
</div>
<!-- end  --> <!-- forth row -->
<div class="col-span-1 md:col-span-2 lg:col-span-3">
<div class="bg-gradient-to-r from-purple-200 via-pink-400 to-red-300  border border-gray-100 block rounded-3xl overflow-hidden filter drop-shadow-lg ">
<div class=" p-4 md:p-7 ">
<h2 class="font-bold text-sec text-xl md:text-2xl font-title py-3">Network Infrastructure Hardware Installation</h2>
<p class="text-gen pb-5">Patch Panels, Hubs, Switches and Routers, supplied, installed configured to meet operational requirements.</p>
</div>
</div>
</div>
<!-- end  --> <!-- fifth row -->
<div class="col-span-1 md:col-span-2 lg:col-span-3">
<div class="bg-gradient-to-r from-purple-200 via-pink-400 to-red-300  border border-gray-100 block rounded-3xl overflow-hidden filter drop-shadow-lg  ">
<div class=" p-4 md:p-7 ">
<h2 class="font-bold text-sec text-xl md:text-2xl font-title py-3">Server Installation and Client PC Installation</h2>
<p class="text-gen pb-5">Server Hardware and Server Software installed and configured as per agreed requirements. Client Applications Software installed as per standard install procedures, customised to particular requirements.</p>
</div>
</div>
</div>
<!-- end  --> <!-- six row -->
<div class="col-span-1 md:col-span-2 lg:col-span-3 mb-10">
<div class="bg-gradient-to-r from-green-300 to-blue-400  border border-gray-100 block rounded-3xl overflow-hidden filter drop-shadow-lg  ">
<div class=" p-4 md:p-7 ">
<h2 class="font-bold text-sec text-xl md:text-2xl font-title py-3">Installation Hand-over</h2>
<p class="text-gen pb-5">Once the installation has been completed to the customer\'s satisfaction, the documentation is handed over and the necessary support agreements put into place.</p>
</div>
</div>
</div>
<!-- end  --></div>
</section>
</div>',
                'image' => 'pages/February2022/92vjZiOr39slasWqGOs0.jpg',
                'slug' => 'network-installation',
                'meta_description' => 'Network Installation',
                'meta_keywords' => 'Network Installation',
                'status' => 'ACTIVE',
                'created_at' => '2022-02-14 11:53:10',
                'updated_at' => '2022-02-17 14:55:47',
            ),
            2 => 
            array (
                'id' => 4,
                'author_id' => 1,
                'title' => 'Network Maintenance',
            'excerpt' => 'OPU POWER offers flexibile Computer Network Maintenance Agreements, which include: Hardware Maintenance, Operating System Support, Network Support, (Network Operating System & Network Infrastructure Support), Backup Software Support, Antivirus Software Support & Application Software Support.',
                'body' => '<div class="container mb-10 px-4 mx-auto max-w-5xl">
<div class="text-center py-5 sm:py-14">
<h1 class="text-3xl md:text-5xl font-bold font-title text-sec">IT Network<br /><span class="text-pri"> Maintenance </span></h1>
</div>
<hr />
<div class="text-center py-10  sm:py-14">
<h5 class="font-title text-gen opacity-100"><span class="text-sec text-3xl">OPU</span> <span class="text-pri text-3xl">POWER</span> offers flexibile Computer Network Maintenance Agreements, which include: Hardware Maintenance, Operating System Support, Network Support, (Network Operating System &amp; Network Infrastructure Support), Backup Software Support, Antivirus Software Support &amp; Application Software Support.</h5>
</div>
<div class="text-center px-10 rounded-2xl py-10 bg-our-bg shadow sm:py-14">
<h5 class="font-title text-gen opacity-100"><span class="text-sec text-2xl">HARDWARE</span> <span class="text-sec text-2xl">MAINTENANCE</span><br /><br />The standard Maintenance Agreement specifies a maximum response time and provides on-site repair with all parts, labour and travel costs included. Loan equipment is available if on-site repair is not achieved, to ensure that the client&rsquo;s business is not affected by lack of facilities.</h5>
</div>
<div class="text-center mt-5 px-10 rounded-2xl py-10 bg-our-bg shadow sm:py-14">
<h5 class="font-title text-gen opacity-100"><span class="text-sec text-2xl">NETWORK INFRASTRUCTURE</span> <span class="text-sec text-2xl">MAINTENANCE</span><br /><br />Provides maintenance cover for patch panels, patch leads, fixed cabling, wall sockets, fly leads and the networking functionality of client PC&rsquo;s, Laptops and Network Printers.</h5>
</div>
<section class="main">
<div class=" grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-y-8 sm:gap-y-12 lg:gap-y-16 gap-x-8 lg:gap-x-12"><!-- first row -->
<div class="col-span-1 md:col-span-2 lg:col-span-3">
<div class=" block md:flex rounded-3xl overflow-hidden transition duration-300 ">
<div class="md:w-1/2 sm:mt-8 "><img class="" src="/storage/pages/main.jpg" alt="" width="500px" /></div>
<div class="md:w-1/2 p-4 md:p-7 my-8 ">
<div class=" mt-10  px-7 py-7 text-center rounded text-sec "><a href="tel:00447912040903"> <button class="px-12 shadow py-2 md:mr-10 rounded-3xl bg-sec text-white hover:bg-blue-800 focus:outline-none mt-4 sm:mt-0 sm:-ml-12" type="submit"> Call us </button></a></div>
<div class=" mt-3  px-7 py-7 text-center rounded text-sec "><a href="mailto:opupower@yahoo.com"> <button class="px-12 shadow py-2 md:mr-10 rounded-3xl bg-sec text-white hover:bg-blue-800 focus:outline-none mt-4 sm:mt-0 sm:-ml-12" type="submit"> Send E-mail </button></a></div>
</div>
</div>
</div>
<!-- end  --></div>
</section>
</div>',
                'image' => 'pages/February2022/6CsLuFawRcB4dhiNPmNN.jpg',
                'slug' => 'network-maintenance',
                'meta_description' => 'IT Network Maintenance',
                'meta_keywords' => 'IT Network Maintenance',
                'status' => 'ACTIVE',
                'created_at' => '2022-02-14 11:55:37',
                'updated_at' => '2022-02-17 14:55:03',
            ),
            3 => 
            array (
                'id' => 5,
                'author_id' => 1,
                'title' => 'Cloud Solutions',
                'excerpt' => 'Cloud computing can transform your business and, improve efficiencies and foster the kind of collaborative culture needed for remote working. There is a range of solutions available depending on your business needs. Whether you need to migrate between providers, purchase a solution or migrate an entire system to the cloud, we provide the tailored advice and support you need to succeed.',
                'body' => '<div class="container mb-10 px-4 mx-auto max-w-5xl">
<div class="text-center py-5 sm:py-14">
<h1 class="text-3xl md:text-5xl font-bold font-title text-sec">Cloud<br /><span class="text-pri"> Solutions </span></h1>
</div>
<hr />
<div class="text-center py-10  sm:py-14">
<h5 class="font-title text-gen opacity-100">Cloud computing can transform your business and, improve efficiencies and foster the kind of collaborative culture needed for remote working. There is a range of solutions available depending on your business needs. Whether you need to migrate between providers, purchase a solution or migrate an entire system to the cloud, we provide the tailored advice and support you need to succeed.</h5>
</div>
<div class="text-center px-10 rounded-2xl py-10 bg-our-bg shadow sm:py-14">
<h5 class="font-title text-gen opacity-100"><span class="text-sec text-2xl">OUR CLOUD SOLUTIONS</span> <span class="text-sec text-2xl">FOR BUSINESSES</span><br /><br />Microsoft Office 365 is the leading Software-as-a-Service cloud solution, and for good reason. It offers a comprehensive suite of productivity and communication apps to help your business streamline operations and improve collaboration whether you work remotely or in the office.We provide a holistic MS Office 365 cloud service covering everything from procurement, migration and support service. We&rsquo;ll work with you to deliver the flexible and scalable solution your business needs while working hard to keep subscriptions and costs to a minimum.If you need to procure any other SaaS services like Azure or AWS , we can advise and assist procurement and deployment, ensuring integration is configured (where required) all whilst acting as the single trusted point of contact for each subscription &ndash; helping you to maximise the benefit of the services procured.</h5>
</div>
<div class=" mt-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-y-8 sm:gap-y-12 lg:gap-y-16 gap-x-8 lg:gap-x-12 text-center px-10 rounded-2xl py-10  sm:py-14">
<div class=""><img class="w-21 h-13" src="/storage/pages/aws.png" alt="" /></div>
<div class=""><img class="w-25 h-20" src="/storage/pages/365.png" alt="" /></div>
<div class=""><img class="w-25 h-15" src="/storage/pages/azure.png" alt="" /></div>
</div>
<hr />
<section class="main">
<div class=" grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-y-8 sm:gap-y-12 lg:gap-y-16 gap-x-8 lg:gap-x-12"><!-- first row -->
<div class="col-span-1 md:col-span-2 lg:col-span-3">
<div class=" block md:flex rounded-3xl overflow-hidden transition duration-300 ">
<div class="md:w-1/2 sm:mt-8 "><img class="" src="/storage/pages/data.jpg" alt="" width="500px" /></div>
<div class="md:w-1/2 p-4 md:p-7 my-8 ">
<div class=" mt-10  px-7 py-7 text-center rounded text-sec "><a href="tel:00447912040903"> <button class="px-12 shadow py-2 md:mr-10 rounded-3xl bg-sec text-white hover:bg-blue-800 focus:outline-none mt-4 sm:mt-0 sm:-ml-12" type="submit"> Call us </button></a></div>
<div class=" mt-3  px-7 py-7 text-center rounded text-sec "><a href="mailto:opupower@yahoo.com"> <button class="px-12 shadow py-2 md:mr-10 rounded-3xl bg-sec text-white hover:bg-blue-800 focus:outline-none mt-4 sm:mt-0 sm:-ml-12" type="submit"> Send E-mail </button></a></div>
</div>
</div>
</div>
<!-- end  --></div>
</section>
</div>',
                'image' => 'pages/February2022/9y7cNOVOS1TW3sFYErGh.jpg',
                'slug' => 'cloud-solutions',
                'meta_description' => 'Cloud Solutions',
                'meta_keywords' => 'Cloud Solutions',
                'status' => 'ACTIVE',
                'created_at' => '2022-02-14 11:58:31',
                'updated_at' => '2022-02-17 14:54:39',
            ),
            4 => 
            array (
                'id' => 6,
                'author_id' => 1,
                'title' => 'Telephone Systems',
                'excerpt' => 'VOIP phone systems require extra care to network design and installation in order to receive clear-noise free audio output from VOIP phones.',
                'body' => '<div class="container mb-10 px-4 mx-auto max-w-5xl">
<div class="text-center py-5 sm:py-14">
<h1 class="text-3xl md:text-5xl font-bold font-title text-sec">VOIP Phone System <br /><span class="text-pri"> for Businesses </span></h1>
</div>
<hr />
<div class="text-center py-10  sm:py-14">&nbsp;</div>
<!-- first row -->
<div class="col-span-1 md:col-span-2 lg:col-span-3">
<div class=" block md:flex rounded-3xl overflow-hidden ">
<div class="md:w-1/2"><img class="" src="/storage/pages/voip.jpg" alt="" /></div>
<div class="md:w-1/2 p-4 md:p-7">
<h2 class="font-bold text-center text-xl md:text-2xl font-title mt-12 text-sec ">LANDLINE BECOMING A SMART PHONE SYSTEM</h2>
<h5 class="font-title text-gen opacity-100">VOIP phone systems require extra care to network design and installation in order to receive clear-noise free audio output from VOIP phones. <span class="text-sec text-3xl"><br />OPU</span> <span class="text-pri text-3xl">POWER</span> have trained professionals who can offer that extra care. We set up telephone systems to get the most out of your landline. Whether for a business or a home owner, we will install a system that will answer calls in the order that you want, redirect them to you wherever you are (worldwide) or automatically process calls to the correct person.</h5>
</div>
</div>
</div>
<!-- end  -->
<section class="main">
<div class=" grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-y-8 sm:gap-y-12 lg:gap-y-16 gap-x-8 lg:gap-x-12"><!-- first row -->
<div class="col-span-1 md:col-span-2 lg:col-span-3">
<div class=" block md:flex rounded-3xl overflow-hidden transition duration-300 ">
<div class="md:w-1/2 sm:mt-8 ">&nbsp;</div>
<div class="md:w-1/2 p-4 md:p-7 my-8 ">
<div class=" mt-10  px-7 py-7 text-center rounded text-sec "><a href="tel:00447912040903"> <button class="px-12 shadow py-2 md:mr-10 rounded-3xl bg-sec text-white hover:bg-blue-800 focus:outline-none mt-4 sm:mt-0 sm:-ml-12" type="submit"> Call us </button></a></div>
<div class=" mt-3  px-7 py-7 text-center rounded text-sec "><a href="mailto:opupower@yahoo.com"> <button class="px-12 shadow py-2 md:mr-10 rounded-3xl bg-sec text-white hover:bg-blue-800 focus:outline-none mt-4 sm:mt-0 sm:-ml-12" type="submit"> Send E-mail </button></a></div>
</div>
</div>
</div>
<!-- end  --></div>
</section>
</div>',
                'image' => 'pages/February2022/TrvcqguQWzfQEr8Hu7V8.jpg',
                'slug' => 'telephone-systems',
                'meta_description' => 'Telephone Systems',
                'meta_keywords' => 'Telephone Systems',
                'status' => 'ACTIVE',
                'created_at' => '2022-02-14 12:00:47',
                'updated_at' => '2022-02-17 14:54:14',
            ),
            5 => 
            array (
                'id' => 7,
                'author_id' => 1,
                'title' => 'CCTV',
                'excerpt' => 'CCTV systems specialist offering CCTV installation for homes and businesses in and around London. We provide CCTV packages including cameras, digital video recorder, installation and CCTV maintenance services at competitive prices.',
                'body' => '<div class="container mb-10 px-4 mx-auto max-w-5xl">
<div class="text-center py-5 sm:py-14">
<h1 class="text-3xl md:text-5xl font-bold font-title text-sec">CCTV Solutions <br /><span class="text-pri"> for Businesses and Homes </span></h1>
</div>
<hr /><!-- first row -->
<div class="col-span-1 md:col-span-2 lg:col-span-3">
<div class=" block md:flex rounded-3xl overflow-hidden">
<div class="md:w-1/2 mt-9"><img class="" src="/storage/pages/cctv2.jpg" alt="" /></div>
<div class="md:w-1/2 p-4 md:p-7">
<h2 class="font-bold  text-xl md:text-2xl font-title mt-12 text-sec ">CCTV Systems Installation for Home &amp; Business</h2>
<h5 class="font-title text-gen opacity-100">CCTV systems specialist offering CCTV installation for homes and businesses in and around London. We provide CCTV packages including cameras, digital video recorder, installation and CCTV maintenance services at competitive prices. Our offers are designed to provide maximum security at affordable prices for home or business.</h5>
<h2 class="font-bold  text-xl md:text-2xl font-title  text-sec ">CCTV for Home</h2>
<h5 class="font-title text-gen opacity-100">Home CCTV installation cost depends on system type (HD-TVI or IP), number of cameras and location, storage size and labour.</h5>
<h2 class="font-bold  text-xl md:text-2xl font-title  text-sec ">CCTV for Business</h2>
<h5 class="font-title text-gen opacity-100">We know that security is a prime concern for business owners. Installation of commercial CCTV for your business will provide great benefits including crime deterrent, monitoring of activities, visual identification and record keeping.</h5>
</div>
</div>
</div>
<!-- end  -->
<section class="main">
<div class=" grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-y-8 sm:gap-y-12 lg:gap-y-16 gap-x-8 lg:gap-x-12"><!-- first row -->
<div class="col-span-1 md:col-span-2 lg:col-span-3">
<div class=" block md:flex rounded-3xl overflow-hidden transition duration-300 ">
<div class="md:w-1/2 sm:mt-8 ">&nbsp;</div>
<div class="md:w-1/2 p-4 md:p-7 my-8 ">
<div class=" mt-10  px-7 py-7 text-center rounded text-sec "><a href="tel:00447912040903"> <button class="px-12 shadow py-2 md:mr-10 rounded-3xl bg-sec text-white hover:bg-blue-800 focus:outline-none mt-4 sm:mt-0 sm:-ml-12" type="submit"> Call us </button></a></div>
<div class=" mt-3  px-7 py-7 text-center rounded text-sec "><a href="mailto:opupower@yahoo.com"> <button class="px-12 shadow py-2 md:mr-10 rounded-3xl bg-sec text-white hover:bg-blue-800 focus:outline-none mt-4 sm:mt-0 sm:-ml-12" type="submit"> Send E-mail </button></a></div>
</div>
</div>
</div>
<!-- end  --></div>
</section>
</div>',
                'image' => 'pages/February2022/BHZl2Fk0dgRrkKJg9rwm.jpg',
                'slug' => 'cctv',
                'meta_description' => 'cctv',
                'meta_keywords' => 'cctv',
                'status' => 'ACTIVE',
                'created_at' => '2022-02-14 12:06:51',
                'updated_at' => '2022-02-17 14:53:37',
            ),
        ];

        foreach ($pages as $page) {
            $attributes = $page;
            unset($attributes['id']);
            $attributes['author_id'] = $authorId;

            Page::firstOrCreate(['slug' => $page['slug']], $attributes);
        }
    }
}
