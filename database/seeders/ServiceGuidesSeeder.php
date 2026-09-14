<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class ServiceGuidesSeeder extends Seeder
{
    public function run(): void
    {
        $guides = [
            [
                'slug' => 'booking-a-tow-in-melbournes-south-east',
                'title' => 'Booking a Tow in Melbourne’s South East: What We Need to Know',
                'excerpt' => 'A practical checklist for arranging vehicle collection, choosing a destination and getting a clear quote.',
                'body' => "A useful towing enquiry starts with a clear picture of the job. Ruby100 serves Endeavour Hills and surrounding suburbs in Melbourne’s south east. When you contact us, share the pickup location, vehicle details and intended destination so we can discuss the right transport arrangement.\n\nYour exact pickup location\nGive us the street address and suburb, or a nearby landmark if an address is unavailable. Explain whether the vehicle is on a street, in a driveway, at a workshop or inside a car park. Height restrictions, narrow entrances, steep driveways and locked gates can affect access.\n\nVehicle condition\nTell us the make and model, whether it starts, whether the wheels roll and steer, and whether the keys are available. Mention damage, flat tyres or anything that may make loading more difficult. Photos can help us assess access and vehicle condition before collection.\n\nWhere the vehicle is going\nConfirm the delivery address and check that the workshop or recipient can accept the vehicle. If delivery is outside opening hours, arrange access and a key handover with the recipient first.\n\nConfirm the quote before booking\nAsk what the price covers, whether access or loading conditions could change it, and what arrival window is available. Travel distance, vehicle condition and the time of collection can influence the job. An enquiry is not a confirmed booking until the details have been agreed.\n\nReady to arrange transport? Call Ruby100 or use the quote form with your pickup suburb, destination and vehicle details.",
            ],
            [
                'slug' => 'prepare-your-unwanted-car-for-collection',
                'title' => 'How to Prepare Your Unwanted Car for Collection',
                'excerpt' => 'Make your car removal enquiry straightforward with condition photos, access details and a simple handover checklist.',
                'body' => "An unused or damaged car can take up valuable space. Before arranging removal, gather the details that help Ruby100 assess the vehicle and discuss a collection plan.\n\nDescribe the car accurately\nShare the make, model, approximate year and current condition. Tell us whether it runs, whether major parts are missing, and whether it has damage. Useful photos include the front, rear, both sides and the access route. A cash offer depends on the individual vehicle; confirm the offer and any collection charges before agreeing to the job.\n\nPlan access for collection\nExplain where the vehicle is parked and whether it can roll and steer. Mention blocked access, underground parking or a steep driveway. Keep keys available if you have them and tell the team in advance if you do not.\n\nRemove personal belongings\nCheck the glovebox, console, boot and under the seats. Remove personal documents, toll tags and any belongings you want to keep. Where possible, clear saved contacts and personal data from the vehicle’s infotainment system.\n\nAgree the handover details\nAsk what proof of ownership or authority to release the vehicle is needed. Confirm who will meet the driver, how payment will be handled if an offer has been agreed, and what collection record you will receive. Registration, number plates and insurance should be checked separately with the relevant provider for your circumstances.\n\nSend the vehicle details and pickup suburb through our quote form or WhatsApp. We will discuss suitability, access and availability before collection is confirmed.",
            ],
            [
                'slug' => 'suv-and-non-running-vehicle-transport',
                'title' => 'SUV and Non-Running Vehicle Transport: Plan the Pickup',
                'excerpt' => 'The vehicle details and access information that help us assess a flatbed transport enquiry.',
                'body' => "Transporting an SUV or a vehicle that will not start requires a little more information than simply providing a pickup address. Vehicle size, condition and access all help determine whether a job is suitable for the available equipment.\n\nIdentify the vehicle\nShare the make, model and year, and mention modifications that affect height, weight or ground clearance. Tell us if it is all-wheel drive, electric or hybrid. Transport requirements differ between vehicles, so the loading and transport method must be assessed for the specific model and condition.\n\nExplain what is working\nDoes the vehicle start? Can it roll, steer and be placed in neutral? Are the keys present? Mention a locked wheel, a damaged tyre, low clearance or any visible damage. Do not attempt to force a vehicle to move just to prepare it for collection.\n\nDescribe the loading area\nPhotos of the driveway or parking space can be as useful as photos of the vehicle. Include entrances, overhead obstructions and the space around the car. Let us know about car park height limits or restricted access before a truck is scheduled.\n\nArrange a suitable destination\nCheck the delivery address and the recipient’s opening hours. Confirm that the workshop or property can receive the vehicle and who will take responsibility for the keys.\n\nGet a job-specific quote\nSend the pickup and delivery suburbs along with the vehicle information. Ruby100 will discuss access, equipment suitability and availability before accepting the booking. This preparation helps avoid delays and makes the handover clearer for everyone involved.",
            ],
            [
                'slug' => 'forklift-and-machinery-transport-enquiries',
                'title' => 'Forklift and Machinery Transport: Information for an Accurate Quote',
                'excerpt' => 'Prepare machine specifications, site access details and delivery arrangements before requesting transport.',
                'body' => "A forklift or warehouse machine needs a transport assessment based on its actual specifications. A photo is a useful starting point, but it cannot establish weight, dimensions or whether a particular truck can accept the load.\n\nProvide the machine specifications\nSend the manufacturer, model and the machine’s transport weight from its documentation or data plate. Include length, width and overall height in its transport configuration. A forklift’s lifting capacity is not its own weight, so provide the machine weight separately.\n\nExplain its condition\nTell us whether the machine operates, steers and brakes, and whether keys are available. Mention leaks, damage or accessories that affect transport. Share photographs of the machine and its identification plate where appropriate. Do not alter or dismantle equipment solely for transport without competent assistance.\n\nCheck collection and delivery access\nProvide both site addresses, opening hours and an onsite contact. Describe gate widths, overhead restrictions, surfaces and any site induction or booking requirements. Explain whether there is a designated loading area and whether access is shared with other traffic.\n\nConfirm suitability before scheduling\nRuby100 can assess a machinery transport enquiry using these details. Acceptance depends on the load, available equipment and access at both ends. Sending a request does not confirm that every type or size of machine can be carried.\n\nKeep the handover organised\nArrange for an authorised site representative to be present. Agree the timing and any site requirements in advance, and leave the loading and securing arrangements to the transport operator.\n\nFor an assessment, send the machine specifications, photos, pickup suburb and delivery location through our quote form or WhatsApp.",
            ],
        ];

        foreach ($guides as $guide) {
            Post::query()->firstOrCreate(['slug' => $guide['slug']], $guide + [
                'is_published' => true,
                'published_at' => now(),
            ]);
        }
    }
}
